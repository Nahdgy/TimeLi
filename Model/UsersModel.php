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
        $sql = "SELECT use_id AS Id, use_name AS Name, use_login AS Login, use_pwd AS Pwd, use_statue AS Statue FROM users";

        try
        {
            if(($this->_req = $this->getDb()->query($sql)) !== false)
            {
                $datas = $this->_req->fetchAll(PDO::FETCH_ASSOC);
                return $datas;
            }
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

#Méthodes de récupération d'un utilisateur présent dans la base de données
    public function readOne($id)
    {
        $sql = "SELECT use_id AS Id, use_name AS Name, use_login AS Login, use_pwd AS Pwd, use_statue AS Statue FROM users WHERE use_id = :id";
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
        $sql = "INSERT INTO users (use_name, use_login, use_pwd, use_statue, moo_id) VALUES (:name, :login, :pwd, :statue, 7)";
        try
        {
            if(($this->_req = $this->getDb()->prepare($sql)) !== false)
            {
                if(($this->_req->bindValue(':name',$_POST['name'], PDO::PARAM_STR)) && ($this->_req->bindValue(':login', $_POST['login'], PDO::PARAM_STR)) && ($this->_req->bindValue(':pwd', $pwd, PDO::PARAM_STR)) && ($this->_req->bindValue(':statue', 1, PDO::PARAM_INT)))
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

#Méthodes de mise à jour d'un utilisateur dans la base de données
    public function update($id)
    {
        $sql = "UPDATE users SET use_name = :name, use_login = :login, use_pwd = :pwd, use_statue = :statue WHERE use_id = :id";
        $password = !empty($_POST['pwd']) ? password_hash($_POST['pwd'], PASSWORD_DEFAULT) : $_POST['pwd'];
        try
        {
            if(($this->_req = $this->getDb()->prepare($sql)) !== false)
            {
                if(($this->_req->bindValue(':name', $_POST['name'], PDO::PARAM_STR)) && ($this->_req->bindValue(':login', $_POST['login'], PDO::PARAM_STR)) && ($this->_req->bindValue(':pwd', $password, PDO::PARAM_STR)) && ($this->_req->bindValue(':statue', $_POST['statue'], PDO::PARAM_INT)) && ($this->_req->bindValue(':id', $id, PDO::PARAM_INT)))
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
    
}

?>