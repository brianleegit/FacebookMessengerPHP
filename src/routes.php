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
$app->post('/webhook', function ($request, $response, $args) {
   $body = $request->getParsedBody();
   $file = 'facebook_request.txt';
   // Open the file to get existing content
   $current = file_get_contents($file);
   // Write the contents back to the file
   file_put_contents($file, $body);

});