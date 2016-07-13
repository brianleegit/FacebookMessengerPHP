<?php

use Illuminate\Database\Capsule\Manager as DB;

function CallPost($post, $url, $header = array()){
    $_header   = array('Content-Type: application/json');
    if(isset($header)){
        $_header   = array_merge($_header, $header);
    } 
    $data = json_encode($post);
    //Initiate cURL.
    $ch = curl_init($url);
    //Tell cURL that we want to send a POST request.
    curl_setopt($ch, CURLOPT_POST, 1);
    //Attach our encoded JSON string to the POST fields.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //Set the content type to application/json
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $_header);
    # Return response instead of printing.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    $info = curl_getinfo($ch);
    //Execute the request
    $res = curl_exec($ch);
    curl_close($ch); // close cURL handler
    $result["result"] = $res;
    $result["info"]   = $info;
    return $result;
}
// Routes

$app->get('/createlog', function ($request, $response, $args) {
    try {
        $this->db->schema()->dropIfExists('facebook_logs');
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
        $logs = $this->db->table('facebook_logs')->orderBy('id', 'desc')->get();
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
    //for debugging purpose
    // $this->db->table('facebook_logs')->insert([
    //     ['requests' => json_encode($body)]       
    // ]);
     // Make sure this is a page subscription
    if($body["object"] == "page"){
        // Iterate over each entry
        // There may be multiple if batched
        foreach ($body["entry"] as $entry) {
            $pageID      = $entry["id"];
            $timeofEvent = $entry["time"];
            foreach ($entry["messaging"] as $event){
                if(isset($event["message"])){
                    $senderID           = $event["sender"]["id"];
                    $recipientID        = $event["recipient"]["id"];
                    $timeOfMessage      = $event["timestamp"];
                    $message            = $event["message"];
                    $messageId          = $message["mid"];
                    // You may get a text or attachment but not both
                    $messageText        = $message["text"];
                    $messageAttachments = $message["attachments"];
                    
                    if(isset($messageText)){
                        $reply = "'".$messageText."' have ".strlen($messageText)." Character.";
                    }else{
                        foreach($messageAttachments as $attachment){
                            if($attachment["type"] == "image"){
                                $imageUrl          = $attachment["payload"]["url"];
                                $cognitiveUrl      = "https://api.projectoxford.ai/vision/v1.0/analyze?visualFeatures=Description";
                                $cognitiveHeader   = array("Ocp-Apim-Subscription-Key: ".$_ENV['COGNITIVE_KEY']);
                                $cognitiveBody     = array("url" => $imageUrl);
                                $cognitiveResponse = callPost($cognitiveBody, $cognitiveUrl, $cognitiveHeader);
                                $cognitiveResponse = json_decode($cognitiveResponse["result"], true);
                                $reply             = "I think it is ".$cognitiveResponse["description"]["captions"][0]["text"];
                            }else{
                                $reply  = "I am sorry, I don't know what is it";
                            }
                        }
                    }
                    
                    $post  = array(
                        "recipient" => array("id"   => strval($senderID)),
                        "message"   => array("text" => $reply)
                    );
                    $token         = $_ENV['FB_TOKEN'];
                    $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$token;
                    callPost($post, $url);
                    return $response->withStatus(200);
                }
            }
        }
    }
   
});

