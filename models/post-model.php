<?php
    use MiladRahimi\PhpCrypt\Crypt;
    use Hashids\Hashids;

    class PostModel{
        private $conexion;
        private $table = 'post';
        private $response;

        public function __CONSTRUCT(){
            $this->conexion = new Conexion();
            $this->pdo 		= $this->conexion->getConexion();
            $this->fpdo		= $this->conexion->getFluent();
            $this->response = new Response();
            $this->crypt    = new Crypt('P463');
            $this->hashids  = new Hashids('', 10);
        }
        
        public function getAll(){
            $conex = $this->pdo;
            $sql = 'SELECT
                    u.idUser, p.idPost, u.first_name,
                    u.last_name, p.title, p.content, p.summary,
                    p.data_start, p.data_updated, g.urlImage
                    FROM user_post AS up
                    INNER JOIN user AS u
                    ON up.userId = u.idUser
                    INNER JOIN post AS p
                    ON up.postId = p.idPost
                    INNER JOIN galery AS g
                    ON p.galeryId = g.idGalery
                    ORDER BY p.idPost DESC';
            $query = $conex->prepare($sql);
            $query->execute();
            $result = null;
            if($query->rowCount()!=0){
                $result = array();
                $serverResponse = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($serverResponse as $r) {
                    $r->idUser = $this->hashids->encode($r->idUser);
                    $r->idPost = $this->hashids->encode($r->idPost);
                    array_push($result,$r);
                }
                $status = true;
                $message = "Encontrado con éxito";
            }
            else{
                $result = array();
                $status = false;
                $message = "No se encontro ningun resultado";
            }

            /* 3. retornar valores en un array Response */
            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );

        }

        public function getAllUser($id){
            $idUser = $this->hashids->decode($id)[0];
            $conex = $this->pdo;
            $sql = 'SELECT
                    u.idUser, p.idPost, u.first_name,
                    u.last_name, p.title, p.content, p.summary,
                    p.data_start, p.data_updated, g.urlImage
                    FROM user_post AS up
                    INNER JOIN user AS u
                    ON up.userId = u.idUser
                    INNER JOIN post AS p
                    ON up.postId = p.idPost
                    INNER JOIN galery AS g
                    ON p.galeryId = g.idGalery
                    WHERE up.userId = ?
                    ORDER BY p.idPost DESC';
            $query = $conex->prepare($sql);
            $query->execute(
                array(
                    intval($idUser)
                )
            );
            $result = null;
            if($query->rowCount()!=0){
                $result = array();
                $serverResponse = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($serverResponse as $r) {
                    $r->idUser = $this->hashids->encode($r->idUser);
                    $r->idPost = $this->hashids->encode($r->idPost);
                    array_push($result,$r);
                }
                $status = true;
                $message = "Encontrado con éxito";
            }
            else{
                $result = array();
                $status = false;
                $message = "No se encontro ningun resultado";
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
            $idPost = $this->hashids->decode($id)[0];
            $conex = $this->pdo;
            $sql = 'SELECT
                    u.idUser, p.idPost, u.first_name,
                    u.last_name, p.title, p.content, p.summary,
                    p.data_start, p.data_updated,
                    g.idGalery, g.urlImage
                    FROM user_post AS up
                    INNER JOIN user AS u
                    ON up.userId = u.idUser
                    INNER JOIN post AS p
                    ON up.postId = p.idPost
                    INNER JOIN galery AS g
                    ON p.galeryId = g.idGalery
                    WHERE up.postId = ?
                    ORDER BY p.idPost DESC';
            $query = $conex->prepare($sql);
            $query->execute(
                array(
                    intval($idPost)
                )
            );
            $result = null;
            /* 2. encriptar IDs */
            if($query->rowCount()!=0){
                $result = $query->fetchObject();
                $result->idUser = $this->hashids->encode($result->idUser);
                $result->idPost = $this->hashids->encode($result->idPost);
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
            /* 0. Verificamos si esta habilitado */
            $idUser = $this->hashids->decode($data->idUser)[0];
            $where = array(
                "idUser"=>$idUser,
                "status"=> new FluentLiteral("1"),
            );
            $queryUser = $this->fpdo->from('user')->where($where)->orderBy('idUser DESC')->limit(1)->execute();   

            if($queryUser->rowCount() != 0){
                /* 1. Guardamos Imagen */
                $image = new GaleryModel();
                $idImage = $image->saveImage($data->coverImage);
                /* 2. Guardamos Post */
                $values = array(
                    'title' => $data->title,
                    'content' => $data->content,
                    'summary' => $data->summary,
                    'galeryId' => $idImage,
                    'data_start' => new FluentLiteral("CURRENT_TIMESTAMP"),
                    'data_updated' => new FluentLiteral("CURRENT_TIMESTAMP")
                );
                $query = $this->fpdo->insertInto($this->table)->values($values);
                $idPost = $query->execute();

                /* 3. Guardamos Blog (User-Post) */
                $values = array(
                    'userId' => intval($idUser),
                    'postId' => intval($idPost),
                );
                $query = $this->fpdo->insertInto('user_post')->values($values);
                $idUserPost = $query->execute();
                $result = array('idUserPost'=>$idUserPost);
                $message = "Post guardado con exito";
                $status = true;
            }else{
                $result = -1;
                $message = "Usted esta deshabilitado, no puede publicar";
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
            /* 0. Verificamos si esta habilitado */
            $idUser = $this->hashids->decode($data->idUser)[0];
            $idPost = $this->hashids->decode($data->idPost)[0];
            $where = array(
                "idUser"=>$idUser,
                "status"=> new FluentLiteral("1"),
            );
            $queryUser = $this->fpdo->from('user')->where($where)->orderBy('idUser DESC')->limit(1)->execute();   

            if($queryUser->rowCount() != 0){
                /* Guardamos nuevos datos */
                $values = array(
                    'title' => $data->title,
                    'content' => $data->content,
                    'summary' => $data->summary,
                    'data_updated' => new FluentLiteral("CURRENT_TIMESTAMP"),
                );
                $query = $this->fpdo->update($this->table)->set($values)->where('idPost',$idPost);
                $idInsert = $this->hashids->encode($query->execute());
                $result = array("idInsert"=>$idInsert);
                $message = 'Modificado con exito';
                $status = true;
            }else{
                $result = -1;
                $message = "Usted esta deshabilitado, no puede editar";
                $status = false;
            }

            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }

        public function delete($data){
            /* 1. consulta with FluentPDO */
            $idPost = $this->hashids->decode($data->idPost)[0];
            $conex = $this->pdo;
            $sql = 'SELECT
                    u.idUser, p.idPost, u.first_name,
                    u.last_name, p.title, p.content, p.summary
                    p.data_start, p.data_updated,
                    g.idGalery, g.urlImage
                    FROM user_post AS up
                    INNER JOIN user AS u
                    ON up.userId = u.idUser
                    INNER JOIN post AS p
                    ON up.postId = p.idPost
                    INNER JOIN galery AS g
                    ON p.galeryId = g.idGalery
                    WHERE up.postId = ?
                    ORDER BY p.idPost DESC';
            $query = $conex->prepare($sql);
            $query->execute(
                array(
                    intval($idPost)
                )
            );
            $result = null;
            /* 2. encriptar IDs */
            if($query->rowCount()!=0){
                $result = $query->fetchObject();
                $image = new GaleryModel();
                /*** A. Eliminar post_user ***/
                $where = array(
                    'userId' => intval($result->idUser),
                    'postId' => intval($result->idPost),
                );
                $query = $this->fpdo->deleteFrom('user_post')->where($where);
                $query->execute();
                
                /*** B. Eliminar Post ***/
                $where = array(
                    'idPost' => intval($result->idPost),
                );
                $query = $this->fpdo->deleteFrom('post')->where($where);
                $query->execute();

                /*** C. Eliminar Imagen ***/
                $image->deleteImage($result->idGalery,$result->urlImage);

                $status = true;
                $message = "Eliminado con éxito";
            }
            else{
                $result = null;
                $status = false;
                $message = "El post no existe";
            }

            /* 3. retornar valores en un array */
            return $this->response->send(
                $result,
                $status,
                $message,
                []
            );
        }

    }
?>