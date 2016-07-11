<?php

use Illuminate\Database\Capsule\Manager as DB;
// Routes
$app->get('/test', function ($request, $response, $args) {    
    return $response->write('<form action="webhook" method="post">
        Request: <input type="text" name="request"><br>        
        <input type="submit">
        </form>');
});

$app->get('/createlog', function ($request, $response, $args) {
    try {
        $this->db->schema::dropIfExists('facebook_logs');
        $this->db->schema()->create('facebook_logs', function($table)
        {
            $table->increments('id');
            $table->longText('requests');
            $table->timestamps();
        });
        return $response->write('table created');

    } catch (Exception $e) {
        return $response->write($e->getMessage());        
    }  
});

$app->get('/showlog', function ($request, $response, $args) {
    try {
        $logs = $this->db->table('facebook_logs')->get();
        return var_dump($logs);

    } catch (Exception $e) {
        return $response->write($e->getMessage());        
    }  
});


$app->get('/webhook', function ($request, $response, $args) {
    $verify_token = "brian";
	$request = $request->getQueryParams(); 

    if(!empty($request['hub_verify_token']) && !empty($request['hub_mode']) 
        && $request['hub_mode'] == 'subscribe' && $request['hub_verify_token'] == $verify_token){
      	return $response->write($request['hub_challenge'])->withStatus(200);
    }else {
      	return $response->withStatus(403);	
    }
});

$app->post('/webhook', function ($request, $response, $args) {
    $body = $request->getParsedBody();   
    $this->db->table('facebook_logs')->insert([
        ['requests' => json_encode($body)]       
    ]);
    $token = 'EAAZAsaRTFGuQBANKpVa2EEMZCsMrr9Jx7KZCyEpsRwZBzCGN1hQskQeKIIyNZAakTc5HK8y9AgELB9Pn5iXakLbeUDpKvhJwAwo5BerCa2vZAZBZBFqqHp1jOuuUfoYm127RZBCJWQiyYb6uwoMcFTwkdvocQecvqzzFxIF9u3PEHvQZDZD';
    $req   = json_decode($body);
    $sender = $req['entry'][0]['messaging'][0]['sender']['id'];
    $post  = array(
        "recipient" => array("id" => $sender),
        "message"   => array("text" => "test")
    );
    $post  = json_encode($post);
    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$token;
    //Initiate cURL.
    $ch = curl_init($url);
    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);
    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    //Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    //Execute the request
    $result = curl_exec();

});