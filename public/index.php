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

$authUrl = $provider->getAuthorizationUrl([
    "scope" => ["openid", "email", "profile"]
]);

$_SESSION["oauth2state"] = $provider->getState();

echo '<a href="' . $authUrl . '">Google ile giriş </a>';