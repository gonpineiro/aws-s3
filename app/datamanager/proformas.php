<?php
function proformas(){
  //PROFORMAS
  global $config, $log, $zip;

  // Get real path for our folder
  $rootPath = realpath(PR_PATH);
  $log->insert('[PR] - Iniciando: '.$rootPath.'\*');

  // Create recursive directory iterator
  /** @var SplFileInfo[] $proformas */
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
          // Get real and relative path for current file
          $filePath = $file->getRealPath();
          $relativePath = substr($filePath, strlen($rootPath) + 1);
          // Add current file to archive
          $zip->addFile($filePath,'Sistemas/PROFORMAS/'.$relativePath);
          $count++;
      }
  }
  //$count = number_format(iterator_count($proformas)) - 2;
  $log->insert('[PR] - '.$rootPath.' : '.$count.' Elementos encontrados.');

}
