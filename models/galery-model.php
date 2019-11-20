<?php
    
    use MiladRahimi\PhpCrypt\Crypt;

    class GaleryModel {
        private $conexion;
        private $table = 'galery';
        private $response;

        public function __CONSTRUCT(){
            $this->conexion = new Conexion();
            $this->pdo 		= $this->conexion->getConexion();
            $this->fpdo		= $this->conexion->getFluent();
            $this->response = new Response();
            $this->crypt    = new Crypt('P463');
        }

        public function saveImage($dataImage, $withThumb = true, $format = 'jpeg'){

            $url_picture = 'upload';
            define('UPLOAD_DIRECTION', $url_picture.'/');

            $image = base64_decode(str_replace('data:image/'.$format.';base64,', '', $dataImage));
			$nameImage = uniqid().'.'.$format;
			// Save image original size
            $formImage = imagecreatefromstring($image);
            if($format === 'jpeg'){
                imagejpeg( $formImage, UPLOAD_DIRECTION.$nameImage, 100 );
            }else{
                imagealphablending($formImage, true);
                imagesavealpha($formImage, true);
                imagepng($formImage, UPLOAD_DIRECTION.$nameImage);
            }
            imagedestroy( $formImage );

            if($withThumb){
                // Save thumbnail
                $thumb = new ImageResizer();
                $thumb->smart_resize_image(null,$image,150,75,false,UPLOAD_DIRECTION.'thumb_'.$nameImage);
            }
            
            /* Guardamos Imagen */

            $values = array(
                'urlImage' => $nameImage,
                'data_start' => new FluentLiteral("CURRENT_TIMESTAMP"),
            );

            $query = $this->fpdo->insertInto($this->table)->values($values);
            $idInsert = $query->execute();
            
            return array(
                "idGalery"=>$idInsert,
                "urlImage"=>$nameImage,
            );
        }

        public function deleteImage($idGalery,$fileName){

            $url_picture = 'upload';
            define('UPLOAD_DIRECTION', $url_picture.'/');

            $files = [
                UPLOAD_DIRECTION.$fileName,
                UPLOAD_DIRECTION.'thumb_'.$fileName
            ];
            
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file) or die("Couldn't delete file");
                }
            }
            
            $where = array(
                'idGalery' => intval($idGalery),
            );
            $query = $this->fpdo->deleteFrom('galery')->where($where);
            $query->execute();

        }

        public function saveDirect($data){
            $imageThumb = ($data->thumb === 'true' );
            $imageFormat = $data->format;
            $imageResult = $this->saveImage($data->cover_image, $imageThumb, $imageFormat);
            return $this->response->send(
                $imageResult,
                true,
                "Imagen Guardada",
                []
            );
        }
        public function deleteDirect($data){
            $where = array(
                "idGalery"=>intval($data->idGalery),
            );
            $query = $this->fpdo->from($this->table)->where($where)->execute();
            if($query->rowCount()!=0){
                $result = $query->fetchObject();
                $imageResult = $this->deleteImage($where['idGalery'], $result->urlImage);
                $status = true;
                $message = "Eliminado";
            }
            else{
                $result = null;
                $status = false;
                $message = "La imagen no existe";
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