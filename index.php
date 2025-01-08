<?php

// Charger la configuration ngrok
require_once 'Config/NgrokConfig.php';
$ngrokConfig = new NgrokConfig();
$baseUrl = $ngrokConfig->getCurrentUrl();

if (!$baseUrl) {
    die("L'URL ngrok n'est pas configurée. Veuillez exécuter ./start-dev.sh");
}

// Définir l'URL de base pour l'application
define('BASE_URL', $baseUrl);


session_unset();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();
$page = '';
 require_once 'Config/config.php';
 require_once 'Functions/autoloader.php';
 // Configuration sécurisée des sessions
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();
 require_once 'Functions/tools.php';
 
 $title = 'TimeLi';
 include 'Inc/head.php';
$ctrl = 'UsersController';
if(isset($_GET['ctrl']))
{
    $ctrl = ucfirst(strtolower($_GET['ctrl'])).'Controller';
}

$method = 'landing';
if(isset($_GET['action']))
{
    $method = $_GET['action'];
}

try
{
    if(class_exists($ctrl))
    {
        $controller = new $ctrl;

        if(method_exists($ctrl,$method))
        {
            if(!empty($_GET['id']) && ctype_digit($_GET['id']))
            {
                $controller->$method($_GET['id']);
            }
            else
            {
                $controller->$method();
            }
        }
    }
}
catch(Exception $e)
{
    die($e->getMessage());
}

require_once 'Inc/foot.php';
?>