<?php

return [
  's3'=> [
    'bucket' => 's3.sab5.ar',
    'region' => 'sa-east-1',
    'version' => 'latest',
    'key' => 'AKIAYFGP56IFGY22ZX7V',
    'secret' => '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'
  ],
  'global' => [
    'temp_path' => 'temp/',
  ],
  'ftp' => [
    'server' => 'localhost',
    'user' => 'gonzalo',
    'password' => '1'
  ],
  'spiceworks' => [
    'sw_path' => 'C:/Program Files (x86)/Spiceworks/backup',
    'ftp_path' => 'prueba/sw/',
    'log_path' => 'logs/spiceworks/',
    's3_path' => 'prueba/resguardo/spiceworks/',
  ],
  'inventario' => [
    'ftp_path' => 'prueba/inv/',
    'log_path' => 'logs/inventario/',
    's3_path' => 'prueba/resguardo/inventario/',
  ],
  'mvp' => [
    'ftp_path' => 'prueba/mvp/',
    'log_path' => 'logs/mvp/',
    's3_path' => 'prueba/resguardo/mvp/',
  ],
  'datamanager' => [
    'copy_path' => 'Z:/backup/Datamanager/',
    'db_path' => 'C:\Sistemas\SAB5\Database',
    'proformas_path' => 'C:\Sistemas\PROFORMAS',
    'recibos_path' => 'C:\Sistemas\RECIBOS',
    'ftp_path' => 'prueba/dm',
    'log_path' => 'logs/datamanager/',
    'ftp_log_path' => 'prueba/dm/logs/',
    's3_path' => 'prueba/resguardo/dm/',
  ]
];
