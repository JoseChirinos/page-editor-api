<?php
  class NurseModel{
    private $conexion;
    private $table = 'nurse';
    private $response;

    public function __CONSTRUCT(){
      $this->conexion = new Conexion();
      $this->pdo 		= $this->conexion->getConexion();
      $this->fpdo		= $this->conexion->getFluent();
      $this->response = new Response();
    }
    
    public function getAll(){
      /* 1. consulta with FluentPDO */
      $query = $this->fpdo->from($this->table)->where('enabled=1')->orderBy('id_nurse DESC')->execute();
      $result = null;
      /* 2. encriptar IDs */
      if($query->rowCount()!=0){
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        $status = true;
        $msg = "Lista de cuentas habilitadas";
      }
      else{
        $result = array();
        $status = false;
        $msg = "No existen registros";
      }
      /* 3. retornar valores en un array Response */
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }

    public function getAllDisabled(){
      /* 1. consulta with FluentPDO */
      $query = $this->fpdo->from($this->table)->where('enabled=0')->orderBy('id_nurse DESC')->execute();
      $result = null;
      /* 2. encriptar IDs */
      if($query->rowCount()!=0){
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        $status = true;
        $msg = "Lista de cuentas deshabilitadas";
      }
      else{
        $result = array();
        $status = false;
        $msg = "No existen registros";
      }
      /* 3. retornar valores en un array Response */
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }

    public function getId($id){
      /* 1. consulta with FluentPDO */
      $query = $this->fpdo->from($this->table)->where('id_nurse',$id)->execute();
      $result = null;
      /* 2. encriptar IDs */
      if($query->rowCount()!=0){
        $result = $query->fetchObject();
        $status = true;
        $msg = "Encontrado con éxito";
      }
      else{
        $result = null;
        $status = false;
        $msg = "No se encontro ningun resultado";
      }
      /* 3. retornar valores en un array */
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }

    public function add($data){
      /* 1 Realizamos la conexion */
      $conex = $this->pdo;
      $sql = 'CALL insertNurse(?,?,?,?,?)';
      $query = $conex->prepare($sql);
      $query->execute(
        array(
          $data->rfid,
          $data->first_name,
          $data->last_name,
          $data->ci,
          $data->cellphone
        )
      );
      $objR = $query->fetchObject();
      if(!$objR->failed){
        $result = $objR->idInsert;
        $message = $objR->msg;
        $status = true;
      }else{
        $result = -1;
        $message = "El CI ya fue registrado";
        $status = false;
      }

      return $this->response->send(
        $result,
        $status,
        $message,
        []
      );
    }

    public function update($data){
      /* 1 Realizamos la conexion */
      $conex = $this->pdo;
      $sql = 'CALL updateNurse(?,?,?,?,?)';
      $query = $conex->prepare($sql);
      $query->execute(
        array(
          $data->id_nurse,
          $data->first_name,
          $data->last_name,
          $data->ci,
          $data->cellphone
        )
      );
      $objR = $query->fetchObject();
      if(!$objR->failed){
        $result = array("id_nurse"=>$objR->idInsert);
        $message = $objR->msg;
        $status = true;
      }else{
        $result = array("id_nurse"=>-1);
        $message = $objR->msg;
        $status = false;
      }

      return $this->response->send(
        $result,
        $status,
        $message,
        []
      );
    }
    
    public function disabled($data){
      /* 1. consulta with FluentPDO */
      $disabledData = array(
        "updated"=> new FluentLiteral("CURRENT_TIMESTAMP"),
        "enabled"=> new FluentLiteral("0"),
        "rfid"=>""
      );
      $result = null;
      $query = $this->fpdo->update(
        $this->table
        )->set(
          $disabledData
          )->where(
            'id_nurse',
            $data->id_nurse
            )->execute();
      if($query){
        $status = true;
        $msg = "Cuenta Deshabilitada";
      }else{
        $status = false;
        $msg = "La cuenta no existe o ya esta deshabilitada";
      }
      /* 2. retornar valores en un array */
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }

    public function enabled($data){
      /* 1. consultar si esta en uso el rfid */
      $query = $this->fpdo->from($this->table)->where('rfid',$data->rfid)->limit(1)->execute();
      if($query->rowCount() == 0 ){
        /* 2.1. Habilitar Cuenta */
        $enabledData = array(
          "updated"=> new FluentLiteral("CURRENT_TIMESTAMP"),
          "enabled"=> new FluentLiteral("1"),
          "rfid"=>$data->rfid
        );
        $query = $this->fpdo->update(
          $this->table
          )->set(
            $enabledData
            )->where(
              'id_nurse',
              $data->id_nurse
              )->execute();
        $result = array("id_nurse"=>$data->id_nurse);
        $msg = "Cuenta Habilitada";
        $status = true;
      }else{
        /* 2.2. El rfid ya esta en uso */
        $result = array("id_nurse"=>$query->fetchObject()->id_nurse);
        $msg = "Error, el rfid ya esta registrado";
        $status = false;
      }
      /* 2. retornar valores en un array */
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }
  }

?>