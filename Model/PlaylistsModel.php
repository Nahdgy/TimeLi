<?php

class PlaylistsModel extends CoreModel
{
    private $_req;

    public function __destruct()
    {
        if(!empty($this->_req))
        {
            $this->_req->closeCursor();
        }
    }

    public function readAll()
    {
        $sql = "SELECT play_id AS Id, play_title AS Title, play_count AS Count, play_duration AS Duration, play_creation AS Creation ,play_update AS UpdateAt, play_visibility AS Visibility, 
        use_id AS UserId,
        mus_id AS MusId, mus_title AS MusTitle, mus_duration AS MusDuration,
        jou_id AS JouId, jou_duration AS JouDuration,
        dur_id AS DurId, dur_rangstart AS DurRangstart, dur_rangend AS DurRangend FROM playlist
        JOIN user USING(use_id)
        JOIN music ON mus_id = play_mus_id
        JOIN joueur USING(jou_id)
        JOIN duration USING(dur_id)";
        try
        {
            if(($this->_req = $this->getDb()->prepare($sql)) !== false)
            {
                if($this->_req->execute())
                {
                    $datas = $this->_req->fetchAll(PDO::FETCH_ASSOC);
                    return $datas;
                }
                return false;
            }
        }
        catch(PDOException $e)
        {
            error_log($e->getMessage());
            return [];
        }


    }

    public function readOne($id)
    {
        $sql = "SELECT play_id AS Id, play_title AS Title, play_count AS Count, play_duration AS Duration, play_creation AS Creation ,play_update AS UpdateAt, play_visibility AS Visibility, 
        use_id AS UserId,
        mus_id AS MusId, mus_title AS MusTitle, mus_duration AS MusDuration,
        jou_id AS JouId, jou_duration AS JouDuration,
        dur_id AS DurId, dur_rangstart AS DurRangstart, dur_rangend AS DurRangend FROM playlist
        JOIN user USING(use_id)
        JOIN music ON mus_id = play_mus_id
        JOIN joueur USING(jou_id)
        JOIN duration USING(dur_id) WHERE play_id = :id";
        try
        {
            if(($this->_req = $this->getDb()->prepare($sql)) !== false)
            {
                if($this->_req->execute([':id' => $id]))
                {
                    $datas = $this->_req->fetchAll(PDO::FETCH_ASSOC);
                    return $datas;
                }
            }
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    public function searchGenres($query)
    {
        $sql = "SELECT typ_id, typ_label 
                FROM type 
                WHERE typ_label LIKE :query 
                ORDER BY typ_label 
                LIMIT 10";
                
        $this->_req = $this->getDb()->prepare($sql);
        $this->_req->execute([
            ':query' => '%' . $query . '%'
        ]);
        
        return $this->_req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        try 
        {
            $sql = "INSERT INTO playlist (play_title, play_duration, play_creation, play_visibility, use_id) 
                    VALUES (:title, :duration, NOW(), :visibility, :use_id)";
            
            $duration = $data['travel_time'];
            
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute([
                ':title' => "Playlist " . date('Y-m-d H:i:s'),
                ':duration' => $duration,
                ':visibility' => 'private',
                ':use_id' => $data['use_id']
            ]);

            return $this->getDb()->lastInsertId();
        } 
        catch (PDOException $e) 
        {
            throw new Exception("Erreur lors de la création de la playlist: " . $e->getMessage());
        }
    }

    public function addMusics($data)
    {
        $sql = "INSERT INTO playlist_music (play_id, mus_id) VALUES (:playlist_id, :music_id)";
        $stmt = $this->getDb()->prepare($sql);
        return $stmt->execute([
            ':playlist_id' => $data['playlist_id'],
            ':music_id' => $data['music_id']
        ]);
    }

    public function deleteAll($id)
    {

    }

    public function deleteOne($id)
    {

    }

    public function removeMusic($data)
    {
        $sql = "DELETE FROM playlist_music 
                WHERE play_id = :playlist_id 
                AND mus_id = :music_id";
                
        $stmt = $this->getDb()->prepare($sql);
        return $stmt->execute([
            ':playlist_id' => $data['playlist_id'],
            ':music_id' => $data['music_id']
        ]);
    }

    public function updateDuration($playlist_id)
    {
        $sql = "UPDATE playlist p 
                SET play_duration = (
                    SELECT SUM(m.mus_duration) 
                    FROM music m 
                    JOIN playlist_music pm ON m.mus_id = pm.mus_id 
                    WHERE pm.play_id = :playlist_id
                )
                WHERE p.play_id = :playlist_id";
                
        $stmt = $this->getDb()->prepare($sql);
        return $stmt->execute([':playlist_id' => $playlist_id]);
    }

}

?>
