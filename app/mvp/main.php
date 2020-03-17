<?php
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Carbon\Carbon;
//require 'vendor/autoload.php';
//$config = require('app/config.php');
$conn_name = Carbon::now()->isoFormat('DD_MM_YYYY-hh_mm_ss')."_MVP";
fileExist(LOG_PATH_MVP);
$log = new Log($conn_name, LOG_PATH_MVP);
$backupDatabaseMvp = new Backup_Database('localhost', 'root', 'root', 'mvp', CHARSET);
$resultMvp = $backupDatabaseMvp->backupTables(TABLES, BACKUP_DIR) ? 'OK' : 'KO';
// Use $output variable for further processing, for example to send it by email
$output = $backupDatabaseMvp->getOutput();

//SUBIR ARCHIVO
$log->insert('[BK] - Procesando: '.$backupDatabaseMvp->backupFile.'.gz');

//FTP
$ftp_conn = ftp_connect(FTP_SRV) or die('Could not connect to: '.FTP_SRV);
@ftp_login($ftp_conn, FTP_USER, FTP_PASS);
$log->insert('[FT] - Enviando: '.TEMP_PATH.$backupDatabaseMvp->backupFile.'.gz'.' ---> '.FTP_SRV.'/'.FTP_PATH_MVP);
ftp_put($ftp_conn, FTP_PATH_MVP.$backupDatabaseMvp->backupFile.'.gz', TEMP_PATH.$backupDatabaseMvp->backupFile.'.gz', FTP_BINARY);
ftp_close($ftp_conn);


try {
    $log->insert('[BK] - Enviando: '.$backupDatabaseMvp->backupFile.'.gz'.' ---> '.S3_BUCKET.'/'.S3_PATH_MVP.$backupDatabaseMvp->backupFile.'.gz');
    $s3->putObject([
        'Bucket' => $config['s3']['bucket'],
        'Key'    => S3_PATH_MVP.$backupDatabaseMvp->backupFile.'.gz',
        'Body'   => fopen(TEMP_PATH.$backupDatabaseMvp->backupFile.'.gz', 'rb')
    ]);
    $log->insert('[BK] - '.$backupDatabaseMvp->backupFile.'.gz'.' Enviado.');
  } catch (Aws\S3\Exception\S3Exception $e) {
    echo "There was an error uploading the file.\n";
    echo $e;
}

// SI NO EXISTE NINGUN ERROR, BORRA EL ARCHIVO ENVIADO
if (!isset($e)) {
    $log->insert('[BK] - Eliminando: '.TEMP_PATH.$backupDatabaseMvp->backupFile.'.gz'.' ...');
    unlink(TEMP_PATH.$backupDatabaseMvp->backupFile.'.gz');
    $log->insert('[BK] - '.TEMP_PATH.$backupDatabaseMvp->backupFile.'.gz'.' Eliminado.');

}

$log->insert('[BK] - FIN DEL MODULO MVP');
