<?php
  class EmergencyModel{
    private $conexion;
    private $table = 'emergency';
    private $response;

    public function __CONSTRUCT(){
      $this->conexion = new Conexion();
      $this->pdo 		= $this->conexion->getConexion();
      $this->fpdo		= $this->conexion->getFluent();
      $this->response = new Response();
    }
    
    public function requestEmergency($id_bed){
      $where = array(
        "bed_id"=>$id_bed,
        "enabled"=> new FluentLiteral("1")
      );
      $queryEmergency = $this->fpdo->from('emergency')->where($where)->orderBy('id_emergency DESC')->limit(1)->execute();
      // Verificamos si ya se registro una emergencia de la misma cama
      if($queryEmergency->rowCount() == 0){
        $queryRoomBed = $this->fpdo->from('room_bed')->where('bed_id',$id_bed)->limit(1)->execute();
        // Verificarmos que la cama exista
        if($queryRoomBed->rowCount() != 0){
          // Registramos la emergencia
          $roomBed = $queryRoomBed->fetchObject();
          $values = array(
            "room_id"=> $roomBed->room_id,
            "bed_id"=> $roomBed->bed_id,
            "time_request"=> new FluentLiteral("CURRENT_TIME"),
            "created"=> new FluentLiteral("CURRENT_TIMESTAMP"),
            "enabled"=> new FluentLiteral("1")
          );
          $query = $this->fpdo->insertInto($this->table)->values($values);
          $idInsert = $query->execute();
          var_dump($idInsert);
          $result = array(
            "id_emergency"=> $idInsert
          );
          $msg = "Emergencia solicitada con exito";
          $status = true;
        }else{
          // La cama no esta registrada o asociada a una sala
          $result = null;
          $msg = "La cama no esta registrada";
          $status = false;
        }
      }else{
        // Ya existe una emergencia registrada para esta cama
        $emergency = $queryEmergency->fetchObject();
        $result = array(
          "id_emergency"=> $emergency->id_emergency
        );
        $msg = "Ya se solicito atención";
        $status = true;
      }
      // Respuesta
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }

    public function successEmergency($id_bed, $rfid){
      var_dump($id_bed, $rfid);
      // Verificamos si existe una peticion
      $where = array(
        "bed_id" => $id_bed,
        "enabled"=> new FluentLiteral("1"),
      );
      $request_emergency = $this->fpdo->from($this->table)->where($where)->limit(1)->execute();
      if($request_emergency->rowCount() != 0){ // Si existe una peticion

        // Verificamos si existe la enfermera
        $request_nurse = $this->fpdo->from('nurse')->where('rfid',$rfid)->limit(1)->execute();
        if($request_nurse->rowCount() != 0 ){ // Si existe la enfermera
          $nurse = $request_nurse->fetchObject();
          $emergency = $request_emergency->fetchObject();
          // Generamos una demanda
          $values = array(
            "nurse_id"=> $nurse->id_nurse,
            "emergency_id"=> $emergency->id_emergency,
            "time_attend"=> new FluentLiteral("CURRENT_TIME"),
            "created"=> new FluentLiteral("CURRENT_TIMESTAMP"),
          );
          $insert_demand = $this->fpdo->insertInto('demand')->values($values);
          $insert_id = $insert_demand->execute();
          // Actualizamos la emergencia -> atendida
          $set = array(
            "enabled"=> new FluentLiteral("0")
          );
          $update_emergency = $this->fpdo->update($this->table)->set($set)->where('id_emergency', $emergency->id_emergency)->execute();

          $result = array(
            "id_demand" => $insert_id
          );
          $status = true;
          $msg = "Emergencia Atendida";

        }else{ // No existe la enfermera
          var_dump('ve a mamar a otra persona');
          $result = null;
          $status = false;
          $msg = "Enfermera no autorizada";
        }
      }else{ // No existe peticion
        $result = null;
        $status = false;
        $msg = "No existe una petición";
      }

      // Respuesta
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );

    }

    public function emergencyNow(){
      $where = array(
        "enabled"=> new FluentLiteral("1")
      );
      $query = $this->fpdo->from($this->table)->where($where)->execute();
      if($query->rowCount()!=0){
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        $status = true;
        $msg = "Se encontraron ".count($result)." solicitudes";
      }else{
        $result = null;
        $status = false;
        $msg = "No hay solicitudes";
      }
      // Respuesta
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }

    public function emergencyNowDetail(){
      $query = $this->fpdo->from('view_emergency_now_detail')->execute();
      if($query->rowCount()!=0){
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        $status = true;
        $msg = "Se encontraron ".count($result)." solicitudes";
      }else{
        $result = null;
        $status = false;
        $msg = "No hay solicitudes";
      }
      // Respuesta
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }
    
  }

?>