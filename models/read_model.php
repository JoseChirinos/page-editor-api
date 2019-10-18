<?php
  class ReadModel{
    private $conexion;
    private $table = 'rfid_read';
    private $response;

    public function __CONSTRUCT(){
      $this->conexion = new Conexion();
      $this->pdo 		= $this->conexion->getConexion();
      $this->fpdo		= $this->conexion->getFluent();
      $this->response = new Response();
    }

    public function readRfid($code){
      /* Verficar la existencia del codigo rfid */
      $check = $this->fpdo->from($this->table)->where('rfid',$code)->orderBy('id_rfid_read DESC')->limit(1)->execute();
      if($check->rowCount() != 0 ){
        $rfid = $check->fetchObject();
        // update all
        $query = $this->fpdo->update($this->table)->set(array(
          "enabled"=> new FluentLiteral("0")
        ))->execute();
        // update rfid
        $set = array(
          "enabled"=> new FluentLiteral("1"),
          "updated"=> new FluentLiteral("CURRENT_TIMESTAMP")
        );
        $query = $this->fpdo->update($this->table)->set($set)->where('id_rfid_read', $rfid->id_rfid_read)->execute();
        $result = array(
          "id_rfid_read"=>$rfid->id_rfid_read
        );
        $status = true;
        $msg = "Rfid habilitado";
      }else{
        // update all
        $query = $this->fpdo->update($this->table)->set(array(
          "enabled"=> new FluentLiteral("0")
        ))->execute();
        // insert rfid
        $values = array(
          "rfid"=> $code,
          "enabled"=> new FluentLiteral("1"),
          "created"=> new FluentLiteral("CURRENT_TIMESTAMP"),
          "updated"=> new FluentLiteral("CURRENT_TIMESTAMP")
        );
        $query = $this->fpdo->insertInto($this->table)->values($values);
        $insert = $query->execute();
        $result = array(
          "id_rfid_read"=>$insert
        );
        $status = true;
        $msg = "RFID registrado y habilitado";
      }

      // Retornar valores en un array
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }

    public function readNow(){
      $query = $this->fpdo->from($this->table)->where('enabled=1')->limit(1)->execute();
      if($query->rowCount() != 0){
        $code = $query->fetchObject();
        // update all
        $query = $this->fpdo->update($this->table)->set(array(
          "enabled"=> new FluentLiteral("0")
        ))->execute();
        // Verificar disponibilidad
        $query = $this->fpdo->from('nurse')->where('rfid',$code->rfid)->limit(1)->execute();
        if($query->rowCount()!=0){
          $nurse = $query->fetchObject();
          $result = array(
            "rfid"=>$code->rfid,
            "enabled"=> false,
            "user"=> array(
              "id_nurse"=> $nurse->id_nurse,
              "first_name"=> $nurse->first_name,
              "last_name"=> $nurse->last_name
            )
          );
        }else{
          $result = array(
            "rfid"=>$code->rfid,
            "enabled"=> true,
            "user"=> null
          );
        }
        $status = true;
        $msg = "RFID encontrado";
      }else{
        $result = null;
        $status = false;
        $msg = "No se detectó tarjeta RFID";
      }
      // Retornar valores en un array
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }
  }
?>