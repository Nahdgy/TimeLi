<?php

class MusicModel extends CoreModel
{
    private $_req;
 
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
    public function create($data)
    {
        $sql = "INSERT INTO music (mus_title, mus_release, mus_duration, mus_rating, mus_link, alb_id, aut_id, moo_id, typ_id, cou_id, spotify_id) VALUES (:title, :release, :duration, :rating, :link, :album_id, :artist_id, :mood_id, :type_id, :country_id, :spotify_id)";
        $stmt = $this->getDb()->prepare($sql);
        return $stmt->execute($data);
    }
}