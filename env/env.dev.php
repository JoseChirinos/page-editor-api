<?php

class Env{

  public function __CONSTRUCT(){
  }
  
  public function getUser(){
    return "root";
  }
  public function getPass(){
    return "";
  }
  public function getHost(){
    return "localhost";
  }
  public function getDataBaseName(){
    return "page_editor_db";
  }
}

?>
