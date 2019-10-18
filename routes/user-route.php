<?php

    $app->get('/user/all', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
          $obj = new UserModel();
          $app->response->status(200);
          $app->response->body(json_encode( $obj->getAll() ));
        }catch(PDOException $e) {
          $app->response->status(500);
          $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->get('/user/all/disabled', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $obj = new UserModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->getAllDisabled() ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->get('/user/:id', function($id) use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $obj = new UserModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->getId($id) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->post('/user/add', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $objDatos = json_decode(file_get_contents("php://input"));
            $obj = new UserModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->add($objDatos) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->post('/user/update', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $objDatos = json_decode(file_get_contents("php://input"));
            $obj = new UserModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->update($objDatos) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->post('/user/disabled', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $objDatos = json_decode(file_get_contents("php://input"));
            $obj = new UserModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->disabled($objDatos) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->post('/user/enabled', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $objDatos = json_decode(file_get_contents("php://input"));
            $obj = new UserModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->enabled($objDatos) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->post('/user/change/password', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $objDatos = json_decode(file_get_contents("php://input"));
            $obj = new UserModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->changePassword($objDatos) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->post('/user/check/force', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $objDatos = json_decode(file_get_contents("php://input"));
            $obj = new UserModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->checkUser($objDatos) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->get('/user/time', function() use($app){
        //var_dump(getdate());
        /* only test */
        // use Hashids\Hashids;
        // $hashids = new Hashids('', 10);
        // echo $hashids->encode(1);

        // $crypt = new Crypt();
        // $r = $crypt->encrypt("This is an important content!");
        // echo $r.'<br />';
        // echo $crypt->decrypt($r);

    });

?>