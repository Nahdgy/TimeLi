#!/bin/bash

# Créer les dossiers
mkdir -p Model View Controller Class Config Functions Inc

# Créer le fichier Config
cat << EOF > Config/Config.php
<?php
 # APP TAG
  define('APP_TAG', 'adminO3W');

  # DATABASE
  define('DB_ENGINE', 'mysql');
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'administration');
  define('DB_CHARSET', 'utf8mb4');
  define('DB_USER', 'root');
  define('DB_PWD', '');
EOF

# Créer le fichier Functions
cat << EOF > Functions/autoloader.php
<?php
function loadClass(\$className)
  {
    if(file_exists('class/'.\$className.'.php'))
    {
      require_once 'class/'.\$className.'.php';
    }
  }

  spl_autoload_register('loadClass');
EOF

# Créer le fichier tools.php
cat << EOF > Functions/tools.php
<?php

function debug(\$var){

  echo '<pre>';
  var_dump(\$var);
  echo '</pre>';

}
EOF

cat << EOF > Inc/head.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \$title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

</head>
<body class="bg-body-secondary">
EOF

# Créer le fichier foot.php
cat << EOF > Inc/foot.php
</body>
</html>
EOF

# Créer le fichier navbar.php
cat << EOF > Inc/navbar.php
<?php 

  # DECONNEXION 
  if(isset(\$_GET['logout'])){
    unset(\$_SESSION[APP_TAG]['connected']);
    header('Location: ../login.php');
    exit;
  }

?>

<nav class="navbar  navbar-expand-lg bg-white border-bottom shadow-sm" data-bs-theme="light">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">App</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= \$currentPage === 'dashboard' ? 'active' : '' ?>"  href="dashboard.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= \$currentPage === 'userList' ? 'active' : '' ?>"  href="userList.php">Users List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= \$currentPage === 'userCreate' ? 'active' : '' ?>"  href="userCreate.php">Add a user</a>
        </li>
        <li class="nav-item">
          <a class="nav-link "  href="?logout">Se déconnecter</a>
        </li>
      
    </div>
  </div>
</nav>
EOF

# Créer le fichier index.php
cat << EOF > index.php
<?php
\$page = '';
 require_once 'Config/config.php';
 require_once 'Functions/autoloader.php';
 require_once 'Functions/tools.php';

\$ctrl = '';
if(isset(\$_GET['ctrl']))
{
    \$ctrl = ucfirst(strtolower(\$_GET['ctrl'])).'Controller';
}

\$method = 'index';
if(isset(\$_GET['action']))
{
    \$method = \$_GET['action'];
}

try
{
    if(class_exists(\$ctrl))
    {
        \$controller = new \$ctrl;

        

        if(method_exists(\$ctrl,\$method))
        {
            if(!empty(\$_GET['id']) && ctype_digit(\$_GET['id']))
            {
                \$controller->\$method(\$_GET['id']);
            }
            else
            {
                \$controller->\$method();
            }
        }
    }
}
catch(Exception \$e)
{
    die(\$e->getMessage());
}

require_once 'Inc/foot.php';
EOF
echo "Les fichiers Model, View et Controller ont été créés avec succès."