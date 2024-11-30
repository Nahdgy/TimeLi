<?php
function loadController( string $controller)
{
  if(file_exists('Controller/'.$controller.'.php'))
  {
    require_once 'Controller/'.$controller.'.php';
  }
}

function loadModel(string $model)
{
  if(file_exists('Model/'.$model.'.php'))
  {
    require_once 'Model/'.$model.'.php';
  }
}

function loadClass(string $class)
{
  if (!class_exists($class) && file_exists('Class/'.$class.'.php')) {
    require_once 'Class/'.$class.'.php';
  }
}

spl_autoload_unregister('loadController');
spl_autoload_unregister('loadModel');
spl_autoload_unregister('loadClass');

spl_autoload_register('loadClass');
spl_autoload_register('loadModel');
spl_autoload_register('loadController');