<?php

class Conexion{
  private $env;
  
  public function __CONSTRUCT(){
    $this->env = new Env("a4f4d627ec020feb0409e7bb10e6b332cddb83352860de403d5c8a6f59143d92");
  }
	public function getConexion(){
		try{
			$usuario = $this->env->getUser();
      $pwd = $this->env->getPass();
      $host = $this->env->getHost();
      $db = $this->env->getDataBaseName();
			$conex = new PDO("mysql:host=".$host.";dbname=".$db.";charset=utf8",$usuario,$pwd,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
			$conex->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

		}catch(PDOException $e){
			echo "Error: ".$e->getMessage();
		}
		return $conex;
	}

	public function getFluent(){
		return new FluentPDO($this->getConexion());
	}
}

?>