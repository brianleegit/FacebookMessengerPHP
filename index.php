<?php

$verify_token = "hello"; // Verify token
$token = "EAAZAsaRTFGuQBALD9iz6ipycbeN3tsbZBGZAwOBPDabYw5EVqegnHmWZBvDOAqbtaQXx18Iz1MRjWHETesOkZACWkUfEEALpDhDc8N2QXsv79oZC6ZAJKC3ZCoygLwELgYYfFgdhl63u2hBweVKBZCmkZCYfiqUlfibrzmfKhmQAGGVgZDZD"; // Page token
// Receive something
if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {

    // Webhook setup request
    echo $_REQUEST['hub_challenge'];
} else {
    echo "Fuck";
   
}
