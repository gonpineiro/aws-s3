<?php
function fileExist($path){
  if (!file_exists($path)) {
      mkdir($path, 0777, true);
  }
}
