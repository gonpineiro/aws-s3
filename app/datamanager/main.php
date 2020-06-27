<?php
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Carbon\Carbon;

require 'config.php';
//MODULOS
require 'app/datamanager/databases.php';
require 'app/datamanager/proformas.php';
require 'app/datamanager/recibos.php';

$conn_name = Carbon::now()->isoFormat('DD_MM_YYYY-hh_mm_ss')."_DM";
//fileExist(LOG_PATH_DM);
$log = new Log($conn_name, LOG_PATH_DM);
$name_zip = Carbon::now()->isoFormat('YYYY_MM_DD_HH_mm').'.zip';
// Initialize archive object
$zip = new ZipArchive();
$zip->open(TEMP_PATH.$name_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$log->insert('[--] - Generando .zip: '.$name_zip);

databasesDm();
//proformas();
//recibos();

$log->insert('[--] - Cerrando archivo: '.$name_zip);
$zip->close();
$log->insert('[--] - Cierre: '.$name_zip);

//FTP
if (FTP_SEND_DM) {
    @ftp_login($ftp_conn, FTP_USER, FTP_PASS);
    $log->insert('[FT] - Enviando: '.$name_zip.' ---> '.FTP_SRV.'/'.FTP_PATH_DM);
    ftp_put($ftp_conn, FTP_PATH_DM.$name_zip, TEMP_PATH.$name_zip, FTP_BINARY);
    ftp_close($ftp_conn);
}

//SUBIR ARCHIVO
$log->insert('[BK] - Procesando: '.$name_zip);
try {
    //$log->insert('[BK] - Copiando: '.$name_zip. ' ---> '.COPY_PATH);
    //copy(TEMP_PATH.$name_zip, COPY_PATH.$name_zip);
    //echo 'Se copio '.$config['datamanager']['temp_path'].$name_zip."\n";
    $log->insert('[BK] - Enviando: '.$name_zip.' ---> '.S3_BUCKET.'/'.S3_PATH_DM.$name_zip);
    $s3->putObject([
        'Bucket' => S3_BUCKET,
        'Key'    => S3_PATH_DM.$name_zip,
        'Body'   => fopen(TEMP_PATH.$name_zip, 'rb')
    ]);

    $log->insert('[BK] - '.$name_zip.' Enviado.');
    $log->insert('[BK] - Eliminando: '.TEMP_PATH.$name_zip.' ...');
    
    $log->insert('[BK] - '.TEMP_PATH.$name_zip.' Eliminado.');
} catch (Aws\S3\Exception\S3Exception $e) {
    echo "There was an error uploading the file.\n";
    echo $e;
}

$log->insert('[BK] - FIN DEL MODULO DATAMANAGER');
//ENVIAR LOGS A FTP.
// echo FTP_LOG_PATH_DM.$conn_name.'.log'.'\n';
// echo LOG_PATH_DM.$conn_name.'.log'.'\n';
// @ftp_login($ftp_conn, FTP_USER, FTP_PASS);
// $log->insert('[FT] - Enviando: '.LOG_PATH_DM.$conn_name.'.log'.' ---> '.FTP_SRV.'/'.FTP_LOG_PATH_DM);
// fclose(LOG_PATH_DM.$conn_name.'.log');
// ftp_put($ftp_conn, FTP_LOG_PATH_DM.$conn_name.'.log', LOG_PATH_DM.$conn_name.'.log', FTP_ASCII);
// ftp_close($ftp_conn);
