<?php
  class DemandModel{
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
      $query = $this->fpdo->from('view_history_attend')->orderBy('id_demand DESC')->execute();
      $result = null;
      /* 2. encriptar IDs */
      if($query->rowCount()!=0){
        $result = $query->fetchAll(PDO::FETCH_OBJ);
        $status = true;
        $msg = "Lista atenciones realizadas";
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
  }

?>