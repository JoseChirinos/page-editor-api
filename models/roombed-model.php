<?php

  class RoomBedModel{
    private $conexion;    
    private $response;

    public function __CONSTRUCT(){
      $this->conexion = new Conexion();
      $this->pdo 		= $this->conexion->getConexion();
      $this->fpdo		= $this->conexion->getFluent();
      $this->response = new Response();
    }
    
    public function getAll(){
      /* 1. consulta with FluentPDO */
      $conex = $this->pdo;
      $sql = 'SELECT * FROM view_room_bed';
      $query = $conex->prepare($sql);
      $query->execute();
      $result = array();
      /* 2. encriptar IDs */
      if($query->rowCount()!=0){
        $elements = $query->fetchAll(PDO::FETCH_OBJ);
        $last = -1;
        $index = -1;
        foreach ($elements as $key => $value) {
          if($last != $value->id_room){
            $last = $value->id_room;
            $index++;
            $result[$index]['beds'] = array();  
          }
          $result[$index]['id_room'] = $value->id_room;
          $result[$index]['room_label'] = $value->room_label;
          $result[$index]['enabled'] = false;
          array_push($result[$index]['beds'], array("id_bed"=>$value->id_bed, "bed_label"=>$value->bed_label));
        }
        $status = true;
        $msg = "Se encontro ".count($result)." elementos.";
      }else{
        $status = false;
        $msg = "Aún no se registros salones con camas";
      }
      return $this->response->send(
        $result,
        $status,
        $msg,
        []
      );
    }
    
  }

?>