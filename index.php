<?php
$page = '';
 require_once 'Config/config.php';
 require_once 'Functions/autoloader.php';
 session_start();
 require_once 'Functions/tools.php';

 include 'Inc/head.php';
 include 'Inc/navbar.php';

$ctrl = '';
if(isset($_GET['ctrl']))
{
    $ctrl = ucfirst(strtolower($_GET['ctrl'])).'Controller';
}

$method = 'index';
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
