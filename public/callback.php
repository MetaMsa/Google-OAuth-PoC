<?php
session_start();

use Dotenv\Dotenv;

require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

use League\OAuth2\Client\Provider\Google;

$provider = new Google([
    "clientId" => $_ENV["GOOGLE_CLIENT_ID"],
    "clientSecret" => $_ENV["GOOGLE_CLIENT_SECRET"],
    "redirectUri" => "http://localhost:8080/callback.php"
]);

if(!isset($_GET["code"])){
    exit("Hata");
}
elseif(empty($_GET["state"]) || ($_GET["state"] !== $_SESSION["oauth2state"])){
    unset($_SESSION["oauth2state"]);
    exit("CSRF riski");
}

try{
    $token = $provider->getAccessToken("authorization_code", [
        "code" => $_GET["code"]
    ]);

    $user = $provider->getResourceOwner($token);
    $data = $user->toArray();

    echo '<h2>Hoşgeldin' . htmlspecialchars($data["name"]) . '</h2>';
    echo '<pre>';
    print_r($user->toArray());
    echo '</pre>';
    echo '<a href="./logout.php">Çıkış</a>';
}
catch(\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e){
    exit($e->getMessage());
}