<?php
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Carbon\Carbon;
require 'config.php';
$conn_name = Carbon::now()->isoFormat('DD_MM_YYYY-hh_mm_ss')."_INV";
fileExist(LOG_PATH_INV);
$log = new Log($conn_name, LOG_PATH_INV);
$backupDatabase = new Backup_Database('localhost', 'root', 'root', 'inventario', CHARSET);
$result = $backupDatabase->backupTables(TABLES, BACKUP_DIR) ? 'OK' : 'KO';
// Use $output variable for further processing, for example to send it by email
$output = $backupDatabase->getOutput();

//SUBIR ARCHIVO
$log->insert('[BK] - Procesando: '.$backupDatabase->backupFile.'.gz');

//FTP
if (FTP_SEND_INV) {
  $ftp_conn = ftp_connect(FTP_SRV) or die('Could not connect to: '.FTP_SRV);
  @ftp_login($ftp_conn, FTP_USER, FTP_PASS);
  $log->insert('[FT] - Enviando: '.TEMP_PATH.$backupDatabase->backupFile.'.gz'.' ---> '.FTP_SRV.'/'.S3_PATH_INV);
  ftp_put($ftp_conn, FTP_PATH_INV.$backupDatabase->backupFile.'.gz', TEMP_PATH.$backupDatabase->backupFile.'.gz', FTP_BINARY);
  ftp_close($ftp_conn);
}

try {
    $log->insert('[BK] - Enviando: '.$backupDatabase->backupFile.'.gz'.' ---> '.S3_BUCKET.'/'.S3_PATH_INV.$backupDatabase->backupFile.'.gz');
    $s3->putObject([
        'Bucket' => S3_BUCKET,
        'Key'    => S3_PATH_INV.$backupDatabase->backupFile.'.gz',
        'Body'   => fopen(TEMP_PATH.$backupDatabase->backupFile.'.gz', 'rb')
    ]);
    $log->insert('[BK] - '.$backupDatabase->backupFile.'.gz'.' Enviado.');
  } catch (Aws\S3\Exception\S3Exception $e) {
    echo "There was an error uploading the file.\n";
    echo $e;
}

// SI NO EXISTE NINGUN ERROR, BORRA EL ARCHIVO ENVIADO
if (!isset($e)) {
    $log->insert('[BK] - Eliminando: '.TEMP_PATH.$backupDatabase->backupFile.'.gz'.' ...');
    unlink(TEMP_PATH.$backupDatabase->backupFile.'.gz');
    $log->insert('[BK] - '.TEMP_PATH.$backupDatabase->backupFile.'.gz'.' Eliminado.');

}

$log->insert('[BK] - FIN DEL MODULO INVENTARIO');
