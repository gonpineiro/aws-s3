<?php
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Carbon\Carbon;
require 'vendor/autoload.php';
$config = require('app/config.php');
$removeDirectory = FALSE;

function databasesSw(){
  //DATABASE
  global $config, $log, $zip, $removeDirectory, $s3;
  // Get real path for our folder
  $rootPath = realpath(SW_PATH);

  $log->insert('[SW] - Iniciando: '.$rootPath.'\*');
  // Create recursive directory iterator
  /** @var SplFileInfo[] $databases */
  $files = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($rootPath),
      RecursiveIteratorIterator::LEAVES_ONLY
  );

  //INICIO DE CONEXION

  $count = 0;
  foreach ($files as $name => $file)

  {
            // Skip directories (they would be added automatically)
      if (!$file->isDir())
      {

          $name_zip = Carbon::now()->isoFormat('YYYYMMDDHHmmss').'.zip';
          // Initialize archive object
          $zip = new ZipArchive();
          $zip->open(TEMP_PATH.$name_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE);
          $log->insert('[--] - Generando .zip: '.$name_zip);

          // Get real and relative path for current file
          $filePath = $file->getRealPath();
          $relativePath = substr($filePath, strlen($rootPath) + 1);
          // Add current file to archive
          $zip->addFile($filePath, $relativePath);

          $log->insert('[--] - Cerrando archivo: '.$name_zip);
          $zip->close();
          $log->insert('[--] - Cierre: '.$name_zip);

          //FTP
          $ftp_conn = ftp_connect(FTP_SRV) or die('Could not connect to: '.FTP_SRV);
          @ftp_login($ftp_conn, FTP_USER, FTP_PASS);
          $log->insert('[FT] - Enviando: '.$name_zip.' ---> '.FTP_SRV.'/'.FTP_PATH_SW);
          ftp_put($ftp_conn, FTP_PATH_SW.$name_zip, TEMP_PATH.$name_zip, FTP_BINARY);
          ftp_close($ftp_conn);

          //SUBIR ARCHIVO
          try {
              $log->insert('[BK] - Enviando: '.$name_zip.' ---> '.S3_BUCKET.'/'.S3_PATH_SW.$name_zip);
              $s3->putObject([
                  'Bucket' => S3_BUCKET,
                  'Key'    => S3_PATH_SW.$name_zip,
                  'Body'   => fopen(TEMP_PATH.$name_zip, 'rb')
              ]);
              $log->insert('[BK] - '.$name_zip.' Enviado.');
              if($removeDirectory){
                  $log->insert('[BK] - Eliminando: '.$relativePath);
                  unlink($filePath); //BORRAR ORIGEN

              }

              $log->insert('[BK] - Eliminiando: '.TEMP_PATH.$name_zip);
              unlink(TEMP_PATH.$name_zip);

          } catch (Aws\S3\Exception\S3Exception $e) {
              echo "There was an error uploading the file.\n";
              echo $e;
          }

      }

  }

}
