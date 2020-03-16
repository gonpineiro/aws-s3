<?php
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
function s3datamanager(){
  //INICIO DE CONEXION
  global $config, $log, $zip, $name_zip;
  $credentials = new Aws\Credentials\Credentials($config['s3']['key'], $config['s3']['secret']);
  $s3 = S3Client::factory([
    'credentials' => $credentials,
    'region' => $config['s3']['region'],
    'version' => $config['s3']['version']
  ]);
  //SUBIR ARCHIVO
  $log->insert('[BK] - Procesando: '.$name_zip);
  try {
      copy($config['datamanager']['temp_path'].$name_zip, $config['datamanager']['copy_path'].$name_zip);
      $log->insert('[BK] - Copiando: '.$name_zip. ' ---> '.$config['datamanager']['copy_path']);
      //echo 'Se copio '.$config['datamanager']['temp_path'].$name_zip."\n";
      $log->insert('[BK] - Enviando: '.$name_zip.' ---> '.$config['s3']['bucket'].'/prueba/resguardo/dm/'.$name_zip);
      $s3->putObject([
          'Bucket' => $config['s3']['bucket'],
          'Key'    => 'prueba/resguardo/dm/'.$name_zip,
          'Body'   => fopen($config['datamanager']['temp_path'].$name_zip, 'rb')
      ]);
      $log->insert('[BK] - '.$name_zip.' Enviado.');
      $log->insert('[BK] - Eliminando: '.$config['datamanager']['temp_path'].$name_zip.' ...');
      unlink($config['datamanager']['temp_path'].$name_zip);
      $log->insert('[BK] - '.$config['datamanager']['temp_path'].$name_zip.' Eliminado.');

      } catch (Aws\S3\Exception\S3Exception $e) {
          echo "There was an error uploading the file.\n";
          echo $e;
      }
}
