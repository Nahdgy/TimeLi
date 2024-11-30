<?php

class UsersController 
{
    public function index()
    {
        $modelUsers = new UsersModel();
        $datas = $modelUsers->readAll();
        

        $users = [];
        

        if(count($datas) > 0)
        {
            foreach($datas as $data)
            {
                $users[] = new Users($data);
            }
            
           return $users;
        } 
        if(!isset($_GET['action']))
        {
            include './View/users/index.php';
        }
        
        
    }
    public function login()
    {
        $users = $this->index();
        if(isset($_POST['submit']))
        {
            
            $login = $_POST['login'];
            $pwd = $_POST['pwd'];
            
            foreach($users as $user)
            {
                if($login === $user->getLogin() && password_verify($pwd, $user->getPwd()))
                {
                    header('Location: index.php?ctrl=home&action=index&id='.$user->getId());
                    return $_SESSION['timeLi']['user'] = $user;
                }
                else
                {
                    header('Location: index.php?ctrl=Users&action=login&login=error');
                }
            }
        }
        include './View/users/connexion.php';
        
    }
    public function register()
    {
        if(isset($_POST['submit']))
        {
            $pwd = $_POST['pwd'];
            $confirmPwd = $_POST['confirmPwd'];
            if($pwd === $confirmPwd)
            {
                $pwd = password_hash($pwd, PASSWORD_DEFAULT);
            }
            else
            {
                header('Location: index.php?ctrl=Users&action=register&pwd=error');
            }

            $modelUsers = new UsersModel();
            $id = $modelUsers->create($pwd);

            if($id)
            {
                header('Location: index.php?ctrl=Users&action=login');
            }
            else
            {
                header('Location: index.php?ctrl=Users&action=register&id=error');
            }
            
        }
        include './View/users/registration.php';
    }
    public function logout()
    {
        session_destroy();
        header('Location: index.php?ctrl=home&action=index');
    }
    public function delete()
    {
        if(isset($_GET['id']) && isset($_SESSION['timeLi']['user']) && $_SESSION['timeLi']['user']->getId() == $_GET['id'] && isset($_POST['submit']))
        {
            $modelUsers = new UsersModel();
            $modelUsers->delete($_GET['id']);
            $_SESSION['timeLi']['message'] = "Votre compte a bien été supprimé";
            $this->logout();
        }
        else
        {
            header('Location: index.php?ctrl=home&action=index');
        }
        
    }
    public function update()
    {
        if(isset($_POST['submit']))
        {
            $model = new UsersModel();
            $model->update($_GET['id']);
            header('Location: index.php?ctrl=users&action=index');
 
        }
        else
        {
            header('Location: index.php');
        }
    }

}

?>