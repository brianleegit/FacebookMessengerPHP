<?php
// Routes
$app->get('/test', function ($request, $response, $args) {
  return $response->write("fuck");
});

// $app->get('/webhook', function ($request, $response, $args) {
// 	$verify_token = "brian";
// 	$request = $request->getQueryParams();  
 
//   if(!empty($request['hub_verify_token']) && !empty($request['hub_mode']) && $request['hub_mode'] == 'subscribe' && $request['hub_verify_token'] == $verify_token){
//   	return $response->write($request['hub_challenge'])->withStatus(200);
//   }else {
//   	return $response->withStatus(403);	
//   }
// });

// $app->post('/webhook', function ($request, $response, $args) {
//    $body = $request->getParsedBody();
   

// });