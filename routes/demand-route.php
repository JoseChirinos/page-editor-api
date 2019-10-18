<?php

  $app->get('/demand/all', function() use($app){
    $app->response->headers->set('Content-type','application/json');
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    try {
      $obj = new DemandModel();
      $app->response->status(200);
      $app->response->body(json_encode( $obj->getAll() ));
    }catch(PDOException $e) {
      $app->response->status(500);
      $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
    }
  });
?>