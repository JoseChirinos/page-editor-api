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

        public function saveImage($dataImage){

            $url_picture = 'upload';
            define('UPLOAD_DIRECTION', $url_picture.'/');

            $image = base64_decode(str_replace('data:image/jpeg;base64,', '', $dataImage));
			$nameImage = uniqid().'.jpg';
			// Save image original size
			$formImage = imagecreatefromstring($image);
			imagejpeg( $formImage, UPLOAD_DIRECTION.$nameImage, 100 );
			imagedestroy( $formImage );
			// Save thumbnail
			$thumb = new ImageResizer();
            $thumb->smart_resize_image(null,$image,150,75,false,UPLOAD_DIRECTION.'thumb_'.$nameImage);
            
            /* Guardamos Imagen */

            $values = array(
                'urlImage' => $nameImage,
                'data_start' => new FluentLiteral("CURRENT_TIMESTAMP"),
            );

            $query = $this->fpdo->insertInto($this->table)->values($values);
            $idInsert = $query->execute();
            
            return $idInsert;
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
    }
?>