$access_token = "EAAZAsaRTFGuQBAHkWDMAHNZAoGUyHFnRGEinLFT08SZAtqkDGUNeZBMLC0BZCj4mS9w4pgQaFfTHKZCHqMG8fCWcyFdTB3nEfdKLGp8WZB2CDHZCSsWWAZBiJp2WPuTbfJwsLql6xAqLEN0EHwuyCzvNxLTM7gC0Jyd5rOraLyCJrQQZDZD";
$verify_token = "brian";
$hub_verify_token = null;
 
if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}
 
 
if ($hub_verify_token === $verify_token) {
    echo $challenge;
}