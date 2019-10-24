<?php

// class GaleryModel{
// 	private $conexion;
// 	private $table = 'galery';
// 	private $response;

// 	public function __CONSTRUCT(){
// 		$this->conexion = new Conexion();
// 		$this->pdo 		= $this->conexion->getConexion();
// 		$this->fpdo		= $this->conexion->getFluent();
// 		$this->response = new Response();
// 		$this->security = new Security();
// 	}

// 	public function getGalery($vista, $id) {
// 		//0. Desencriptar id
// 		$idd = trim($this->security->desencriptar($id));
// 		//1. Inicializando variables
// 		$result = [];
// 		$msg = null;
// 		$status = false;
// 		$error = '';
// 		$datos = null;

// 		//2. Realizando la conexión por PDO y ejecutamos la consulta
// 		$conex = $this->pdo;

// 		$sql = "SELECT * FROM $vista WHERE id_common=$idd";
// 		$result = $conex->prepare($sql);
// 		$result->execute();
// 		$conex = null;
// 		if($result->rowCount()!=0) {
// 			$status = true;
// 			$msg = 'Datos encontrados.';
// 			$datos = array();
// 			$serverResponse = $result->fetchAll(PDO::FETCH_OBJ);
// 			foreach ($serverResponse as $r) {
// 				$r->id = $this->security->encriptar($r->id);
// 				array_push($datos,$r);
// 			}
// 		}

// 		//3. retornar valores en un array Response
// 		return $this->response->send(
// 			$datos,
// 			$status,
// 			$msg,
// 			$error
// 		);
// 	}

// 	public function updateGalery($id_g, $id_c, $type, $table) {
//     //0. Desencriptar id
// 		$id_galery = trim($this->security->desencriptar($id_g));
// 		$id_common = trim($this->security->desencriptar($id_c));    
// 		//1. Inicializando variables
// 		$result = [];
// 		$msg = null;
// 		$status = false;
// 		$error = '';
// 		$datos = null;

// 		//2. Realizando la conexión por PDO y ejecutamos la consulta
//     $conex = $this->pdo;
// 		$sql = 'CALL pUpdateGalery(?,?,?,?)';
// 		//Preparando el procedimiento almacenado
// 		$query = $conex->prepare($sql);
// 		//Ejecutando el procedimiento almacenado
// 		$query->execute(
// 			array(
// 				$id_galery,
// 				$id_common,
// 				$type,
// 				$table
// 			)
// 		);
// 		$conex = null;

// 		//Recive la respuesta de la consulta
// 		//$query->fetchObject() solo se puede ejecutar una vez
//     //Para la siguiente la variable $query se vacía
//     $objR = $query->fetchObject();
    
// 		//Convertir la cadena a un valor booleano
// 		$objR->status = $objR->status === 'true' ? true : false;

// 		if($objR->status) { //Si Todo esta bien carga los datos
// 			if( isset($objR->idUpdate) ) {
// 				$result = array('idUpdate'=>$this->security->encriptar($objR->idUpdate),'url_picture'=>$objR->url_picture);
// 			}
// 		}
// 		$status = $objR->status;
// 		$msg = $objR->msg;
// 		$error = $objR->error;
// 		//$msg = array('id_g'=>$id_g,'id_c'=>$id_c,'type'=>$type,'table'=>$table,'id_galery'=>$id_galery,'id_common'=>$id_common);

// 		//3. Retornando el resultado
// 		return $this->response->send(
// 			$result,
// 			$status,
// 			$msg,
// 			$error
//     );
// 	}

// 	public function newGalery($config, $data) {
// 		/*
// 		$data = base64_decode(str_replace('data:image/jpeg;base64,', '', $data->image));
// 		$formImage = imagecreatefromstring($data);
// 		imagejpeg( $formImage, "../upload/profile/output.jpg", 100 );
// 		imagedestroy( $formImage );
// 		$tumb = new ImageResizer();
// 		$tumb->smart_resize_image(null,$data,75,75,false,'../upload/profile/tumb_output.jpg');
// 		*/
// 		$result = [];
//     $status = false;
// 		$msg = '';
//     $error = '';
// 		$conex = $this->pdo;
// 		$pre = '';

// 		if( !is_numeric($config['id_common']) )
// 			$config['id_common'] = trim($this->security->desencriptar($config['id_common']));

// 		if( $config['belong'] == 'business' )
// 			$pre = 'b_';
// 		if( $config['belong'] == 'user' )
// 			$pre = 'u_';

// 		//$url_picture = '../upload/'.$pre.$config['type'].'/'.$config['id_common'];
// 		$url_picture = '../upload/'.$pre.$config['type'];

// 		if($this->verifyType($config['type'])){
// 			if(!is_dir( $url_picture ))
// 			  mkdir($url_picture, 0664);
// 			define('UPLOAD_DIRECTION', $url_picture.'/');

// 			$image = base64_decode(str_replace('data:image/jpeg;base64,', '', $data->image));
// 			$nameImage = $this->security->encriptar(uniqid()).'.jpg';
// 			// Save image original size
// 			$formImage = imagecreatefromstring($image);
// 			imagejpeg( $formImage, UPLOAD_DIRECTION.$nameImage, 100 );
// 			imagedestroy( $formImage );
// 			// Save thumbnail
// 			$thumb = new ImageResizer();
// 			$thumb->smart_resize_image(null,$image,75,75,false,UPLOAD_DIRECTION.'thumb_'.$nameImage);

// 			// Preparando Consulta
// 			$sql = 'CALL insertGalery(?,?,?,?)';
// 			//Preparando el procedimiento almacenado
// 			$query = $conex->prepare($sql);
// 			//Ejecutando el procedimiento almacenado
// 			$query->execute(
// 				array(
// 					$nameImage,
// 					$config['type'],
// 					$config['belong'],
// 					$config['id_common']
// 				)
// 			);
// 			$conex = null;
// 			//Recibe la respuesta de la consulta
// 			//$query->fetchObject() solo se puede ejecutar una vez
// 			//Para la siguiente la variable $query se vacía
// 			$objR = $query->fetchObject();
// 			//Convertir la cadena a un valor booleano
// 			$objR->status = $objR->status === 'true' ? true : false;

// 			if($objR->status) { //Si Todo esta bien carga los datos
// 				if( isset($objR->idInsertado) ) {
// 					$result = $this->security->encriptar($objR->idInsertado);
// 				}
// 				$status = $objR->status;
// 				$msg = $objR->msg;
// 				$error = $objR->error;
// 			} else { //En caso de recibir otra respuesta de parte del procedimiento almacenado
// 				$status = $objR->status;
// 				$msg = $objR->msg;
// 				$error = $objR->error;
// 			}

// 		}else{
// 			$msg = 'No tiene un type definido';
// 			$status = false;
// 		}

//     return $this->response->send(
// 			$result,
// 			$status,
// 			$msg,
// 			$error
// 		);

//   }
  

//   public function newGaleryJoin($config, $data) {
// 		$result = [];
//     $status = false;
// 		$msg = '';
//     $error = '';
// 		$conex = $this->pdo;
// 		$pre = '';

// 		if( !is_numeric($config['id_common']) )
// 			$config['id_common'] = trim($this->security->desencriptar($config['id_common']));

// 		if( $config['belong'] == 'business' )
// 			$pre = 'b_';
// 		if( $config['belong'] == 'user' )
// 			$pre = 'u_';

// 		//$url_picture = '../upload/'.$pre.$config['type'].'/'.$config['id_common'];
// 		$url_picture = '../upload/'.$pre.$config['type'];

// 		if($this->verifyType($config['type'])){
// 			if(!is_dir( $url_picture ))
// 			  mkdir($url_picture, 0664);
// 			define('UPLOAD_DIRECTION', $url_picture.'/');

// 			$image = base64_decode(str_replace('data:image/jpeg;base64,', '', $data->image));
// 			$nameImage = $this->security->encriptar(uniqid()).'.jpg';
// 			// Save image original size
// 			$formImage = imagecreatefromstring($image);
// 			imagejpeg( $formImage, UPLOAD_DIRECTION.$nameImage, 100 );
// 			imagedestroy( $formImage );
// 			// Save thumbnail
// 			$thumb = new ImageResizer();
// 			$thumb->smart_resize_image(null,$image,75,75,false,UPLOAD_DIRECTION.'thumb_'.$nameImage);

// 			// Preparando Consulta
// 			$sql = 'CALL insertGaleryJoin(?,?,?,?)';
// 			//Preparando el procedimiento almacenado
// 			$query = $conex->prepare($sql);
// 			//Ejecutando el procedimiento almacenado
// 			$query->execute(
// 				array(
// 					$nameImage,
// 					$config['type'],
// 					$config['belong'],
// 					$config['id_common']
// 				)
// 			);
// 			$conex = null;
// 			//Recibe la respuesta de la consulta
// 			//$query->fetchObject() solo se puede ejecutar una vez
// 			//Para la siguiente la variable $query se vacía
// 			$objR = $query->fetchObject();
// 			//Convertir la cadena a un valor booleano
// 			$objR->status = $objR->status === 'true' ? true : false;

// 			if($objR->status) { //Si Todo esta bien carga los datos
// 				if( isset($objR->idInsertado) ) {
// 					$result = $this->security->encriptar($objR->idInsertado);
// 				}
// 				$status = $objR->status;
// 				$msg = $objR->msg;
// 				$error = $objR->error;
// 			} else { //En caso de recibir otra respuesta de parte del procedimiento almacenado
// 				$status = $objR->status;
// 				$msg = $objR->msg;
// 				$error = $objR->error;
// 			}

// 		}else{
// 			$msg = 'No tiene un type definido';
// 			$status = false;
// 		}

//     return $this->response->send(
// 			$result,
// 			$status,
// 			$msg,
// 			$error
// 		);

// 	}
// 	private function verifyType($type) {
// 		$typeDefine = array('cover','picture','category','profile');
// 		foreach ($typeDefine as $key) {
// 			if($type == $key) return true;
// 		}
// 		return false;
// 	}

// }

?>
