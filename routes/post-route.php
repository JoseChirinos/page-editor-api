<?php

    use Hashids\Hashids;

    $app->get('/post/all', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
          $obj = new PostModel();
          $app->response->status(200);
          $app->response->body(json_encode( $obj->getAll() ));
        }catch(PDOException $e) {
          $app->response->status(500);
          $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });
    $app->get('/post/all/:id', function($id) use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $obj = new PostModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->getAllUser($id) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->get('/post/:id', function($id) use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $obj = new PostModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->getId($id) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->post('/post/add', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $objDatos = json_decode(file_get_contents("php://input"));
            $obj = new PostModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->add($objDatos) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->post('/post/update', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $objDatos = json_decode(file_get_contents("php://input"));
            $obj = new PostModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->update($objDatos) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->post('/post/delete', function() use($app){
        $app->response->headers->set('Content-type','application/json');
        $app->response->headers->set('Access-Control-Allow-Origin','*');
        try {
            $objDatos = json_decode(file_get_contents("php://input"));
            $obj = new PostModel();
            $app->response->status(200);
            $app->response->body(json_encode( $obj->delete($objDatos) ));
        }catch(PDOException $e) {
            $app->response->status(500);
            $app->response->body(json_encode( array('result'=>[],'status'=>false,'message'=>$e->getMessage(),'error'=>'500') ));
        }
    });

    $app->get('/post/test', function() use($app){
        //var_dump(getdate());
        /* only test */
        $hashids = new Hashids('', 10);
        echo $hashids->decode('VolejRejNm')[0];

        // $crypt = new Crypt();
        // $r = $crypt->encrypt("This is an important content!");
        // echo $r.'<br />';
        // echo $crypt->decrypt($r);

    });

?>