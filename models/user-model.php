<?php

    use Hashids\Hashids;

    class UserModel{
        private $conexion;
        private $table = 'user';
        private $response;

        public function __CONSTRUCT(){
            $this->conexion = new Conexion();
            $this->pdo 		= $this->conexion->getConexion();
            $this->fpdo		= $this->conexion->getFluent();
            $this->response = new Response();
            $this->hashids  = new Hashids('', 10);
        }
        
        public function getAll(){
            /* 1. consulta with FluentPDO */
            $query = $this->fpdo->from($this->table)->where('status=1')->orderBy('idUser DESC')->execute();
            $result = null;
            /* 2. encriptar IDs */
            if($query->rowCount()!=0){
                $result = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($result as $key => $value) {
                    $value->idUser = $this->hashids->encode($value->idUser);
                }
                $status = true;
                $message = "Lista de cuentas habilitadas";
            }
            else{
                $result = array();
                $status = false;
                $message = "No existen registros";
            }
            /* 3. retornar valores en un array Response */
            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }

        public function getAllDisabled(){
            /* 1. consulta with FluentPDO */
            $query = $this->fpdo->from($this->table)->where('status=0')->orderBy('idUser DESC')->execute();
            $result = null;
            /* 2. encriptar IDs */
            if($query->rowCount()!=0){
                $result = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($result as $key => $value) {
                    $value->idUser = $this->hashids->encode($value->idUser);
                }
                $status = true;
                $message = "Lista de cuentas deshabilitadas";
            }
            else{
                $result = array();
                $status = false;
                $message = "No existen registros";
            }
            /* 3. retornar valores en un array Response */
            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }

        public function getId($id){
            /* 1. consulta with FluentPDO */
            $idUser = $this->hashids->decode($id)[0];            
            $where = array(
                "idUser"=>$idUser,
            );
            $query = $this->fpdo->from($this->table)->where($where)->execute();
            $result = null;
            /* 2. encriptar IDs */
            if($query->rowCount()!=0){
                $result = $query->fetchObject();
                $result->idUser =  $this->hashids->encode($result->idUser);
                $status = true;
                $message = "Encontrado con éxito";
            }
            else{
                $result = null;
                $status = false;
                $message = "No se encontro ningun resultado";
            }
            /* 3. retornar valores en un array */
            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }

        public function add($data){
            /* 1. Verificamos si ya se registro el email */
            $where = array(
                "email"=>$data->email,
            );
            $queryUser = $this->fpdo->from($this->table)->where($where)->orderBy('idUser DESC')->limit(1)->execute();   
            if($queryUser->rowCount() == 0){
                    /* Insertamos nuevo usuario */
                    $values = array(
                        'first_name' => $data->first_name,
                        'last_name' => $data->last_name,
                        'email' => $data->email,
                        'password' => $data->password,
                        'type_user' => $data->type_user, /* R: Root, A: Admin, P: Publico */
                        'data_start' => new FluentLiteral("CURRENT_TIMESTAMP"),
                        'data_updated' => new FluentLiteral("CURRENT_TIMESTAMP"),
                        'status' => new FluentLiteral("1"),
                    );
                    $query = $this->fpdo->insertInto($this->table)->values($values);
                    $idInsert = $this->hashids->encode($query->execute());
                    $result = array("idInsert"=>$idInsert);
                    $message = 'Insertado con exito';
                    $status = true;
            }else{
                $result = -1;
                $message = "El email ya fue registrado";
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
            /* 1. Verificamos si existe usuario */
            $idUser = $this->hashids->decode($data->idUser)[0];
            $where = array(
                "idUser"=>$idUser,
            );
            $queryUser = $this->fpdo->from($this->table)->where($where)->orderBy('idUser DESC')->limit(1)->execute();   
            if($queryUser->rowCount() != 0){
                    /* Insertamos nuevo usuario */
                    $values = array(
                        'first_name' => $data->first_name,
                        'last_name' => $data->last_name,
                        'type_user' => $data->type_user, /* R: Root, A: Admin, P: Publico */
                        'data_updated' => new FluentLiteral("CURRENT_TIMESTAMP"),
                    );
                    $query = $this->fpdo->update('user')->set($values)->where('idUser',$idUser);
                    $idInsert = $this->hashids->encode($query->execute());
                    $result = array("idInsert"=>$idInsert);
                    $message = 'Modificado con exito';
                    $status = true;
            }else{
                $result = -1;
                $message = "El usuario no esta registrado";
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
            /* 1. Verificamos usuario */
            $idUser = $this->hashids->decode($data->idUser)[0];            
            $where = array(
                "idUser"=>$idUser,
            );
            $queryUser = $this->fpdo->from($this->table)->where($where)->orderBy('idUser DESC')->limit(1)->execute();   
            $result = null;
            if($queryUser->rowCount() != 0){
                    /* Deshabilitamos usuario */
                    $disabledData = array(
                        "data_updated"=> new FluentLiteral("CURRENT_TIMESTAMP"),
                        "status"=> new FluentLiteral("0"),
                    );
                    $query = $this->fpdo->update(
                        $this->table
                        )->set(
                        $disabledData
                        )->where(
                            'idUser',
                            $idUser
                            )->execute();
                    if($query){
                        $status = true;
                        $message = "Cuenta Deshabilitada";
                    }else{
                        $status = false;
                        $message = "La cuenta no existe o ya esta deshabilitada";
                    }
            }else{
                $result = -1;
                $message = "La cuenta no existe o ya esta deshabilitada";
                $status = false;
            }

            /* 2. retornar valores en un array */
            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }

        public function enabled($data){
            /* 1. Verificamos usuario */
            $idUser = $this->hashids->decode($data->idUser)[0];            
            $where = array(
                "idUser"=>$idUser,
            );
            /* 2. consultar si esta en uso el rfid */
            $queryUser = $this->fpdo->from($this->table)->where($where)->orderBy('idUser DESC')->limit(1)->execute();
            $result = null;
            if($queryUser->rowCount() != 0 ){
                /* 2.1. Habilitar Cuenta */
                $enabledData = array(
                    "data_updated"=> new FluentLiteral("CURRENT_TIMESTAMP"),
                    "status"=> new FluentLiteral("1"),
                );
                $query = $this->fpdo->update(
                $this->table
                )->set(
                    $enabledData
                    )->where(
                    'idUser',
                    $idUser
                    )->execute();
                $message = "Cuenta Habilitada";
                $status = true;
            }else{
                $message = "Usuario no registrado";
                $status = false;
            }
            /* 2. retornar valores en un array */
            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }

        public function changePassword($data){
            /* 1. Verificamos usuario */
            $idUser = $this->hashids->decode($data->idUser)[0];
            $where = array(
                "idUser"=>$idUser
            );
            $queryUser = $this->fpdo->from($this->table)->where($where)->orderBy('idUser DESC')->limit(1)->execute();
            $result = null;
            if($queryUser->rowCount() != 0 ){
                /* 2.1. Cambiar Contraseña */
                $userActual = $queryUser->fetchObject();
                $passwordUserActual = $userActual->password;
                
                if($data->password == $passwordUserActual){
                    $values = array(
                        "data_updated"=> new FluentLiteral("CURRENT_TIMESTAMP"),
                        "password"=> $data->new_password,
                    );
                    $query = $this->fpdo->update(
                    $this->table
                    )->set(
                        $values
                    )->where(
                        'idUser',
                        $idUser
                    )->execute();
                    $message = "Contraseña Cambiada";
                    $status = true;
                }else{
                    $message = "La Contraseña Actual que escribio es Incorrecta";
                    $status = false;
                }
            }else{
                $message = "Usuario no registrado";
                $status = false;
            }
            /* 2. retornar valores en un array */
            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }

        public function checkUser($data){
            $idUser = $this->hashids->decode($data->idUser)[0];
            $where = array(
                "idUser"=>$idUser
            );
            $queryUser = $this->fpdo->from($this->table)->where($where)->orderBy('idUser DESC')->limit(1)->execute();
            
            $userActual = $queryUser->fetchObject();
            $result = array(
                "name" => $userActual->first_name.' '.$userActual->last_name,
                "email" => $userActual->email,
                "password" => $userActual->password
            );
            $status = true;
            $message = "Datos encontrados";
                
            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }
        public function signIn($data){
            $where = array(
                "email"=>$data->email,
            );
            $queryUser = $this->fpdo->from('user')->where($where)->orderBy('idUser DESC')->limit(1)->execute();
            if($queryUser->rowCount()!=0){
                $userActual = $queryUser->fetchObject();
                $password = $userActual->password;
                if($password == $data->password ){
                    $result = array(
                        'idUser' => $this->hashids->encode($userActual->idUser),
                        'first_name' => $userActual->first_name,
                        'last_name' => $userActual->last_name,
                        'email' => $userActual->email,
                        'type_user' => $userActual->type_user,
                    );
                    $status = true;
                    $message = "Acceso permitido";
                }else{
                    $result = null;
                    $status = false;
                    $message = "Contraseña incorrecta";
                }
            }else{
                $result = null;
                $status = false;
                $message = "Su correo no esta registrado";
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