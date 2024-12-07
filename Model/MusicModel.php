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
        $sql = "SELECT * FROM music";
        return $this->_req->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function search($query)
    {
        $sql = "SELECT *, aut_name, alb_title FROM music JOIN authors USING (aut_id) JOIN album USING (alb_id) WHERE mus_title LIKE '%$query%' OR art_name LIKE '%$query%' OR alb_title LIKE '%$query%'";

        $stmt = $this->getDb()->prepare($sql);
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Filtres
    public function musicByMood($mood_id)
    {
        $sql = "SELECT * FROM music JOIN mood USING (mood_id) WHERE mood_id = :mood_id";
    }

    public function musicByType($type_id)
    {
        $sql = "SELECT * FROM music JOIN type USING (typ_id) WHERE typ_id = :type_id";
    }

    public function musicByCountry($country_id)
    {
        $sql = "SELECT * FROM music JOIN country USING (cou_id) WHERE cou_id = :country_id";
    }
    public function create($data)
    {
        $sql = "INSERT INTO music (mus_title, mus_release, mus_duration, mus_rating, mus_link, alb_id, aut_id, moo_id, typ_id, cou_id, spotify_id) VALUES (:title, :release, :duration, :rating, :link, :album_id, :artist_id, :mood_id, :type_id, :country_id, :spotify_id)";
        $stmt = $this->getDb()->prepare($sql);
        return $stmt->execute($data);
    }
}