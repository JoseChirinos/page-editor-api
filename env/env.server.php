<?php

class Env{

  public function __CONSTRUCT($key){
  }
  
  public function getUser(){
    return "user_server";
  }
  public function getPass(){
    return "password";
  }
  public function getHost(){
    return "localhost";
  }
  public function getDataBaseName(){
    return "emergencia_db";
  }
  
}

?>
