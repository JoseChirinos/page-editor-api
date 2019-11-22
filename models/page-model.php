<?php

    use MiladRahimi\PhpCrypt\Crypt;
    use Hashids\Hashids;

    class PageModel{
        private $conexion;
        private $table = 'page';
        private $response;

        public function __CONSTRUCT(){
            $this->conexion = new Conexion();
            $this->pdo 		= $this->conexion->getConexion();
            $this->fpdo		= $this->conexion->getFluent();
            $this->response = new Response();
            $this->crypt    = new Crypt('P463');
            $this->hashids  = new Hashids('', 10);
        }
        
        public function getNow(){
            /* 1. consulta with FluentPDO */
            $query = $this->fpdo->from($this->table)->orderBy("idPage DESC")->execute();
            $result = null;
            /* 2. encriptar IDs */
            if($query->rowCount()!=0){
                $result = $query->fetchObject();
                $result->idPage =  $this->hashids->encode($result->idPage);
                $status = true;
                $message = "Pagina obtenida";
            }
            else{
                $result = array();
                $status = false;
                $message = "No se tiene nada aùn";
            }
            /* 3. retornar valores en un array Response */
            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }

        public function save($data){
            /* 1. Verificamos si ya se registro el email */
            $queryPage = $this->fpdo->from($this->table)->orderBy("idPage DESC")->execute();
            if($queryPage->rowCount() != 0){
                // update
                $idPage = $this->hashids->decode($data->idPage)[0];
                $valuesPage = array(
                    "context" => $data->context,
                    "context_order" => $data->context_order,
                    "data_updated" => new FluentLiteral("CURRENT_TIMESTAMP"),
                );
                /* actualizamos la pagina */
                $querySave = $this->fpdo->update($this->table)->set($valuesPage)->where("idPage",$idPage);
                $querySave->execute();
                $idInsert = $this->hashids->encode($idPage);
                $result = array("idPage"=>$idInsert);
                $message = "Insertado con exito";
                $status = true;
            }else{
                // new
                $valuesPage = array(
                    "context" => $data->context,
                    "context_order" => $data->context_order,
                    "data_start" => new FluentLiteral("CURRENT_TIMESTAMP"),
                    "data_updated" => new FluentLiteral("CURRENT_TIMESTAMP"),
                );
                $querySave = $this->fpdo->insertInto($this->table)->values($valuesPage);
                $idInsert = $this->hashids->encode($querySave->execute());
                $result = array("idPage"=>$idInsert);
                $message = "Hello world!";
                $status = true;
            }

            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }
    }

?>