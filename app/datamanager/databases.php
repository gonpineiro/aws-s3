<?php
function databasesDm(){
  //DATABASE
  global $config, $log, $zip;
  // Get real path for our folder
  $rootPath = realpath(DB_PATH);

  $log->insert('[DB] - Iniciando: '.$rootPath.'\*');
  // Create recursive directory iterator
  /** @var SplFileInfo[] $databases */
  $files = new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator($rootPath),
      RecursiveIteratorIterator::LEAVES_ONLY
  );
  $count = 0;
  foreach ($files as $name => $file)
  {
      // Skip directories (they would be added automatically)
      if (!$file->isDir())
      {
          $count++;
          // Get real and relative path for current file
          $filePath = $file->getRealPath();
          $relativePath = substr($filePath, strlen($rootPath) + 1);
          // Add current file to archive
          $zip->addFile($filePath,'Sistemas/SAB5/Database/'.$relativePath);

      }
  }
  //$count = number_format(iterator_count($databases)) - 2;
  $log->insert('[DB] - '.$rootPath.' : '.$count.' Elementos encontrados.');
}
