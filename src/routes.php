<?php
// Routes

$app->get('/webhook', function ($request, $response, $args) {
	$verify_token = "brian";
	$request = $request->getQueryParams();
    $hubmode = $request['hub_mode'];
    $hubverify = $request['hub_verify_token'];
    $challenge = $request['hub_challenge'];
   
    if($hubmode == 'subscribe' && $hubverify == $verify_token){
    	return $response->write($challenge)->withStatus(200);
    }else {
    	return $response->withStatus(403);	
    }
});

$app->get('/test', function ($request, $response, $args) {
    return "test";
});


