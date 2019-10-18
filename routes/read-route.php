<?php

  $app->get('/read/now', function() use($app){
    $app->response->headers->set('Content-type','application/json');
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    try {
      $obj = new ReadModel();
      $app->response->status(200);
      $app->response->body(json_encode( $obj->readNow() ));
    }catch(PDOException $e) {
      $app->response->status(500);
      $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
    }
  });

  $app->get('/read/:code', function($code) use($app){
    $app->response->headers->set('Content-type','application/json');
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    try {
      $obj = new ReadModel();
      $app->response->status(200);
      $app->response->body(json_encode( $obj->readRfid($code) ));
    }catch(PDOException $e) {
      $app->response->status(500);
      $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
    }
  });

//   $app->post('/nurse/add', function() use($app){
//     $app->response->headers->set('Content-type','application/json');
//     $app->response->headers->set('Access-Control-Allow-Origin','*');
//     try {
//       $objDatos = json_decode(file_get_contents("php://input"));
//       $obj = new NurseModel();
//       $app->response->status(200);
//       $app->response->body(json_encode( $obj->add($objDatos) ));
//     }catch(PDOException $e) {
//       $app->response->status(500);
//       $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
//     }
//   });

//   $app->post('/nurse/update', function() use($app){
//     $app->response->headers->set('Content-type','application/json');
//     $app->response->headers->set('Access-Control-Allow-Origin','*');
//     try {
//       $objDatos = json_decode(file_get_contents("php://input"));
//       $obj = new NurseModel();
//       $app->response->status(200);
//       $app->response->body(json_encode( $obj->update($objDatos) ));
//     }catch(PDOException $e) {
//       $app->response->status(500);
//       $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
//     }
//   });

//   $app->post('/nurse/disabled', function() use($app){
//     $app->response->headers->set('Content-type','application/json');
//     $app->response->headers->set('Access-Control-Allow-Origin','*');
//     try {
//       $objDatos = json_decode(file_get_contents("php://input"));
//       $obj = new NurseModel();
//       $app->response->status(200);
//       $app->response->body(json_encode( $obj->disabled($objDatos) ));
//     }catch(PDOException $e) {
//       $app->response->status(500);
//       $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
//     }
//   });

//   $app->post('/nurse/enabled', function() use($app){
//     $app->response->headers->set('Content-type','application/json');
//     $app->response->headers->set('Access-Control-Allow-Origin','*');
//     try {
//       $objDatos = json_decode(file_get_contents("php://input"));
//       $obj = new NurseModel();
//       $app->response->status(200);
//       $app->response->body(json_encode( $obj->enabled($objDatos) ));
//     }catch(PDOException $e) {
//       $app->response->status(500);
//       $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
//     }
//   });

?>