<?php

class MusicModel extends CoreModel
{
    private $_req;

    public function __destruct()
    {
        if(!empty($this->_req))
        {
            $this->_req->closeCursor();
        }
    }
 
    public function index()
    {
        $sql = "SELECT mus_id AS Id, mus_title AS Title, mus_realese AS DateRelease, mus_duration AS Duration, mus_rating AS Rating, mus_link AS Link, alb_id AS AlbumId, aut_id AS ArtistId FROM music";
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
            return $e->getMessage();
        }
    }
    public function search($query)
    {
        $sql = "SELECT m.*, aut_name, alb_title 
                FROM music m 
                JOIN authors a ON m.aut_id = a.aut_id 
                JOIN album al ON m.alb_id = al.alb_id 
                WHERE mus_title LIKE :query 
                OR aut_name LIKE :query 
                OR alb_title LIKE :query";

        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO music (mus_title, mus_release, mus_duration, mus_rating, mus_link, alb_id, aut_id, spotify_id) VALUES (:title, :release, :duration, :rating, :link, :album_id, :artist_id, :spotify_id)";
        $stmt = $this->getDb()->prepare($sql);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM playlist WHERE play_id = :id";
        try
        {
            if(($this->_req = $this->getDb()->prepare($sql)) !== false)
            {
                if($this->_req->execute([':id' => $id]))
                {
                    return true;
                }
            }
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }
}