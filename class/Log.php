<?php
class Log
{
  public function __construct($filename, $path)
  {
      $this->path     = ($path) ? $path : "/";
      $this->filename = ($filename) ? $filename : "log";
      $this->date     = date("Y-m-d H:i:s");
  }
  public function insert($text)
  {

  $log    = $this->date . " [] " . $text . PHP_EOL;
  $result = (file_put_contents($this->path . $this->filename . ".log", $log, FILE_APPEND)) ? 1 : 0;

  return $result;
  }
}
