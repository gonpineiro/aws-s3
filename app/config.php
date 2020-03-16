<?php

return [
  's3'=> [
    'bucket' => 's3.sab5.ar',
    'region' => 'sa-east-1',
    'version' => 'latest',
    'key' => 'AKIAYFGP56IFFFZW7VPF',
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
    'ftp_path' => 'prueba/sw/'
  ],
  'inventario' => [
    'ftp_path' => 'prueba/inv/'
  ],
  'datamanager' => [
    'copy_path' => 'Z:/backup/Datamanager/',
    'db_path' => 'C:\Sistemas\SAB5\Database',
    'proformas_path' => 'C:\Sistemas\PROFORMAS',
    'recibos_path' => 'C:\Sistemas\RECIBOS',
    'ftp_path' => 'prueba/dm/',
    'log_path' => 'logs/datamanager/',
    'ftp_log_path' => 'prueba/dm/logs/',
  ]
];
