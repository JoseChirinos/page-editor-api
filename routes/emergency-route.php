<?php

  $app->get('/emergency/request/:id_bed', function($id_bed) use($app){
    $app->response->headers->set('Content-type','application/json');
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    try {
      $obj = new EmergencyModel();
      $app->response->status(200);
      $app->response->body(json_encode( $obj->requestEmergency($id_bed) ));
    }catch(PDOException $e) {
      $app->response->status(500);
      $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
    }
  });

  $app->get('/emergency/now', function() use($app){
    $app->response->headers->set('Content-type','application/json');
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    try {
      $obj = new EmergencyModel();
      $app->response->status(200);
      $app->response->body(json_encode( $obj->emergencyNow() ));
    }catch(PDOException $e) {
      $app->response->status(500);
      $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
    }
  });

  $app->get('/emergency/now/detail', function() use($app){
    $app->response->headers->set('Content-type','application/json');
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    try {
      $obj = new EmergencyModel();
      $app->response->status(200);
      $app->response->body(json_encode( $obj->emergencyNowDetail() ));
    }catch(PDOException $e) {
      $app->response->status(500);
      $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
    }
  });

  $app->get('/emergency/success/:id_bed/:rfid', function($id_bed, $rfid) use($app){
    $app->response->headers->set('Content-type','application/json');
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    try {
      $obj = new EmergencyModel();
      $app->response->status(200);
      $app->response->body(json_encode( $obj->successEmergency($id_bed,$rfid) ));
    }catch(PDOException $e) {
      $app->response->status(500);
      $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
    }
  });

  $app->get('/test/fecha', function() use($app){
    $m = new \Moment\Moment();
    echo $m->format('[Weekday:] l');
  });
?>