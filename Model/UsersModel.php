<?php

class UsersModel extends CoreModel
{
    private $_req;

    public function __destruct()
    {
        if(!empty($this->_req))
        {
            $this->_req->closeCursor();
        }
    }


#Méthodes de récupération de tous les utilisateurs présents dans la base de données
    public function readAll()
    {
        $sql = "SELECT use_id AS Id, use_firstname AS Firstname, use_lastname AS Lastname, use_email AS Email, use_pwd AS Pwd, use_statue AS Statue, rol_id FROM users WHERE rol_Id = 2";

        try
        {
            if(($this->_req = $this->getDb()->query($sql)) !== false)
            {
                $datas = $this->_req->fetchAll(PDO::FETCH_ASSOC);
                return $datas;
            }
            return false;
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

#Méthodes de récupération d'un utilisateur présent dans la base de données
    public function readOne($id)
    {
        $sql = "SELECT use_id AS Id, use_firstname AS Firstname, use_lastname AS Lastname, use_email AS Email, use_pwd AS Pwd, use_statue AS Statue, rol_id FROM users WHERE use_id = :id";
        try
        {
            if(($this->_req = $this->getDb()->prepare($sql)) !== false)
            {
                if(($this->_req->bindValue(':id', $id, PDO::PARAM_INT)))
                {
                    if($this->_req->execute())
                    {
                        $datas = $this->_req->fetch(PDO::FETCH_ASSOC);
                        return $datas;
                    }
                }
            }
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }

    }

#Méthodes de création d'un utilisateur dans la base de données
    public function create($pwd)
    {
        $sql = "INSERT INTO users(use_firstname, use_lastname, use_email, use_pwd, use_statue, moo_id, rol_id) VALUES (:firstname, :lastname, :email, :pwd, 1, 7, 2)";
        try
        {
            if(($this->_req = $this->getDb()->prepare($sql)) !== false)
            {
                if(($this->_req->bindValue(':firstname',$_POST['firstname'], PDO::PARAM_STR)) && ($this->_req->bindValue(':lastname', $_POST['lastname'], PDO::PARAM_STR)) && ($this->_req->bindValue(':email', $_POST['email'], PDO::PARAM_STR)) && ($this->_req->bindValue(':pwd', $pwd, PDO::PARAM_STR)))
                {
                    if($this->_req->execute())
                    {
                        $res = $this->getDb()->lastInsertId();
                        return $res;
                    }
                }
            }
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }

    }

#Méthode de création d'admin dans la base de données
    public function createAdmin($pwd)
    {
        $sql = "INSERT INTO users (use_firstname, use_lastname, use_mail,use_pwd,use_statue,moo_id,rol_id) VALUES (:firstname, :lastname, :mail, :pwd, 1, 7, 1)";
        try
        {
          if(($this->_req = $this->getDb()->prepare($sql)) !== false)
          {
            if(($this->_req->bindValue(':firstname', $_POST['firstname'])) && ($this->_req->bindValue(':lastname', $_POST['lastname'])) && ($this->_req->bindValue(':mail', $_POST['email'])) && ($this->_req->bindValue(':pwd', $pwd)))
            {
              if($this->_req->execute())
              {
                $res = $this->getDb()->lastInsertId();
                return $res;
              }
            }
          }
        }
        catch(PDOException $e)
        {
          die($e->getMessage());
        }
    }

#Méthodes de suppression d'un utilisateur dans la base de données
    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE use_id = :id";
        try
        {
            if(($this->_req = $this->getDb()->prepare($sql)) !== false)
            {
                if(($this->_req->bindValue(':id', $id, PDO::PARAM_INT)))
                {
                    if($this->_req->execute())
                    {
                        return true;
                    }
                }
            }
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

#Méthodes de récupération des playlists d'un utilisateur
    public function readPlaylists($id)
    {
        $sql = "SELECT * FROM playlists WHERE use_id = :id";
        $this->_req = $this->getDb()->prepare($sql);
        $this->_req->bindValue(':id', $id, PDO::PARAM_INT);
        $this->_req->execute();
        return $this->_req->fetchAll(PDO::FETCH_ASSOC);
    }
#Methodes pour compter les playlist d'un utilisateur
    public function countPlaylists($id)
    {
        $sql = "SELECT COUNT(play_id) AS count FROM playlists WHERE use_id = :id";
        if(($this->_req = $this->getDb()->prepare($sql)) !== false)
        {
            if(($this->_req->bindValue(':id', $id, PDO::PARAM_INT)))
            {
                if($this->_req->execute())
                {
                    $datas = $this->_req->fetch(PDO::FETCH_ASSOC);
                    return $datas['count'];
                }
            }
        }
    }

#Méthodes de mise à jour d'un utilisateur dans la base de données
    public function update($id)
    {
        $sql = "UPDATE users SET use_firstname = :firstname, use_lastname = :lastname, use_email = :email, use_pwd = :pwd, use_statue = :statue WHERE use_id = :id";
        $password = !empty($_POST['pwd']) ? password_hash($_POST['pwd'], PASSWORD_DEFAULT) : $_POST['pwd'];
        try
        {
            if(($this->_req = $this->getDb()->prepare($sql)) !== false)
            {
                if(($this->_req->bindValue(':firstname', $_POST['firstname'], PDO::PARAM_STR)) && ($this->_req->bindValue(':lastname', $_POST['lastname'], PDO::PARAM_STR)) && ($this->_req->bindValue(':email', $_POST['email'], PDO::PARAM_STR)) && ($this->_req->bindValue(':pwd', $password, PDO::PARAM_STR)) && ($this->_req->bindValue(':statue', $_POST['statue'], PDO::PARAM_INT)) && ($this->_req->bindValue(':id', $id, PDO::PARAM_INT)))
                {
                    if($this->_req->execute())
                    {
                        return true;
                    }
                }
            }
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }

    }   
    
    public function updateSpotifyCredentials($userId, $spotifyUserId, $accessToken, $refreshToken)
    {
        $sql = "UPDATE users SET 
                spotify_user_id = :spotify_user_id,
                spotify_access_token = :access_token,
                spotify_refresh_token = :refresh_token
                WHERE use_id = :user_id";
                
        try {
            $stmt = $this->getDb()->prepare($sql);
            return $stmt->execute([
                'spotify_user_id' => $spotifyUserId,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'user_id' => $userId
            ]);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public function anonymizeUser($userId) {
        $sql = "UPDATE users 
                SET use_firstname = 'Anonyme',
                    use_lastname = 'Anonyme',
                    use_email = NULL,
                    use_pwd = NULL,
                    spotify_access_token = NULL,
                    spotify_refresh_token = NULL
                WHERE use_id = :id";
    }
}

?>