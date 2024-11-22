<?php
function loadController( string $controller)
{
  if(file_exists('Controller/'.$controller.'.php'))
  {
    require_once 'Controller/'.$controller.'.php';
  }
}

spl_autoload_register('loadController');


function loadModel(string $model)
{
  if(file_exists('Model/'.$model.'.php'))
  {
    require_once 'Model/'.$model.'.php';
  }
}

spl_autoload_register('loadModel');


function loadClass(string $class)
{
  if(file_exists('Class/'.$class.'.php'))
  {
    require_once 'Class/'.$class.'.php';
  }
}

spl_autoload_register('loadClass');
