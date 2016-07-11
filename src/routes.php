<?php

use Illuminate\Database\Capsule\Manager as DB;
// Routes
$app->get('/test', function ($request, $response, $args) {    
    return $response->write('<form action="webhook" method="post">
        Name: <input type="text" name="name"><br>
        E-mail: <input type="text" name="email"><br>
        <input type="submit">
        </form>');
});

$app->get('/createlog', function ($request, $response, $args) {
    try {
        $this->db->schema()->create('users', function($table)
        {
            $table->increments('id');
            $table->string('requests');
            $table->timestamps();
        });
        return $response->write('table created');

    } catch (Exception $e) {
        return $response->write($e->getMessage());        
    }  
});

$app->get('/webhook', function ($request, $response, $args) {
    $verify_token = "brian";
	$request = $request->getQueryParams();  
 
    if(!empty($request['hub_verify_token']) && !empty($request['hub_mode']) && $request['hub_mode'] == 'subscribe' && $request['hub_verify_token'] == $verify_token){
      	return $response->write($request['hub_challenge'])->withStatus(200);
    }else {
      	return $response->withStatus(403);	
    }
});

$app->post('/webhook', function ($request, $response, $args) {
    $body = json_encode($request->getParsedBody());   
    $this->db->table('users')->insert([
        ['requests' => $body]       
    ]);
});