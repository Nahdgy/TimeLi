<?php

class AdminController
{
    public function index()
    {
       if($_GET['role'] === 'admin' && isset($_SESSION['timeLi']['admin']))
       {
        include './View/admin/index.php';
       }
       else
       {
        header('Location: index.php?ctrl=admin&action=login&role=admin');
       }
    }

    public function usersList()
    {
        $modelUsers = new UsersModel();
        $modelPlaylists = new PlaylistsModel();
        $datasUsers = $modelUsers->readAllUsers();
        $users = [];
        if(count($datasUsers) > 0)
        {
            foreach($datasUsers as $data)
            {
                $users[] = new Users($data);
            }
            
        }

        include './View/admin/usersList.php';
    }

    public function musicList()
    {
        $modelMusic = new MusicModel();
        $datasMusic = $modelMusic->index();
        $musics = [];
        if(count($datasMusic) > 0)
        {
            foreach($datasMusic as $data)
            {
                $musics[] = new MusicClass($data);
            }
        }
        include './View/admin/musicList.php';
    }

    public function moodList()
    {
        $modelPlaylists = new PlaylistsModel();
        $datasMoods = $modelPlaylists->readAllMoods();
        $moods = [];
        if(count($datasMoods) > 0)
        {
            foreach($datasMoods as $data)
            {
                $moods[] = new Mood($data);
            }
        }
        include './View/admin/moodList.php';
    }

    public function genreList()
    {
        $modelPlaylists = new PlaylistsModel();
        $datasGenres = $modelPlaylists->readAllGenres();
        $genres = [];
        if(count($datasGenres) > 0)
        {
            foreach($datasGenres as $data)
            {
                $genres[] = new Genre($data);
            }
        }
        include './View/admin/genreList.php';
    }

    public function countryList()
    {
        $modelPlaylists = new PlaylistsModel();
        $datasCountries = $modelPlaylists->readAllCountries();
        $countries = [];
        if(count($datasCountries) > 0)
        {
            foreach($datasCountries as $data)
            {
                $countries[] = new Country($data);
            }
        }

        include './View/admin/countryList.php';
    }

    public function login()
    {
        
        if(isset($_POST['submit']))
        {
            $modelUsers = new UsersModel();
            $data = $modelUsers->findAdminByEmail($_POST['email']); 
            debug($data);
            debug($_POST['email']);
            if($data)
            { 
                
                $admin = new Users($data);

                $email = $_POST['email'];
                $pwd = $_POST['pwd'];

                debug($admin);

                if($_GET['role'] === 'admin')
                {
                   
                    if($email === $admin->getEmail() && password_verify($pwd, $admin->getPwd()))
                    {
                        echo 'connecté';
                        header('Location: index.php?ctrl=admin&action=index&role=admin');
                        return $_SESSION['timeLi']['admin'] = $admin;
                    }
                    else
                    {
                        header('Location: index.php?ctrl=Users&action=login&role=admin&login=error');
                    }
            
                } 
            }
            else if ($data === false)
            {
                header('Location: index.php?ctrl=Users&action=login&role=admin&login=error');
            }
        }
        include './View/users/connection.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: index.php?ctrl=Admin&action=login&role=admin');
    }

    public function showPlaylists()
    {
        $modelPlaylists = new PlaylistsModel();
        $datas = $modelPlaylists->readPlaylists($_GET['id']);
        $playlists = [];
        if(count($datas) > 0)
        {
            foreach($datas as $data)
            {
                $playlists[] = new Playlist($data);
            }
        }
        include './View/admin/showPlaylists.php';
    }

    public function view()
    {
        $modelUsers = new UsersModel();
        $datas = $modelUsers->readOne($_GET['id']);
        $user = new Users($datas);
        include './View/admin/view.php';
    }

    public function edit()
    {
        
        if(isset($_POST['submit']))
        {
            $modelUsers = new UsersModel();
            $data = $modelUsers->updateByAdmin($_GET['id']);
            if($data)
            {
                header('Location: index.php?ctrl=admin&action=usersList');
            }
        }
    }

    public function editMood()
    {
        if(isset($_POST['submit']))
        {
            $modelPlaylists = new PlaylistsModel();
            $data = $modelPlaylists->updateMood($_GET['id'], $_POST['name']);
            if($data)
            {
                header('Location: index.php?ctrl=admin&action=moodList');
            }
        }
    }   

    public function editGenre()
    {
        if(isset($_POST['submit']))
        {
            $modelPlaylists = new PlaylistsModel();
            $data = $modelPlaylists->updateGenre($_GET['id'], $_POST['name']);
            if($data)
            {
                header('Location: index.php?ctrl=admin&action=genreList');
            }
        }
    }

    public function editCountry()
    {
        if(isset($_POST['submit']))
        {
            $modelPlaylists = new PlaylistsModel();
            $data = $modelPlaylists->updateCountry($_GET['id'], $_POST['name'], $_POST['code']);
            if($data)
            {
                header('Location: index.php?ctrl=admin&action=countryList');
            }
        }
    }

    public function delete()
    {
        if(isset($_POST['submit']))
        {
            $modelUsers = new UsersModel();
            $data = $modelUsers->delete($_GET['id']);
            if($data)
            {
                header('Location: index.php?ctrl=admin&action=index');
            }
        }
    }

    public function deleteMood()
    {
        if(isset($_POST['submit']))
        {
            $modelPlaylists = new PlaylistsModel();
            $data = $modelPlaylists->deleteMood($_GET['id']);
            if($data)
            {
                header('Location: index.php?ctrl=admin&action=moodList');
            }
        }
    }

    public function deleteGenre()
    {
        if(isset($_POST['submit']))
        {
            $modelPlaylists = new PlaylistsModel();
            $data = $modelPlaylists->deleteGenre($_GET['id']);
            if($data)
            {
                header('Location: index.php?ctrl=admin&action=genreList');
            }
        }
    }

    public function deleteCountry()
    {
        if(isset($_POST['submit']))
        {
            $modelPlaylists = new PlaylistsModel();
            $data = $modelPlaylists->deleteCountry($_GET['id']);
            if($data)
            {
                header('Location: index.php?ctrl=admin&action=countryList');
            }
        }
    }

    public function deleteMusic()
    {
        if(isset($_POST['submit']))
        {
            $modelMusic = new MusicModel();
            $data = $modelMusic->delete($_GET['id']);
            if($data)
            {
                header('Location: index.php?ctrl=admin&action=musicList');
            }
        }
    }
}

