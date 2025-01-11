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
        $sql = "SELECT 
        play_id AS Id, 
        play_title AS Title, 
        play_count AS Count, 
        play_duration AS Duration, 
        play_creation AS Creation,
        play_update AS UpdateAt, 
        play_visibility AS Visibility, 
        use_id AS UserId,
        mood.moo_id AS MoodId, moo_label AS MoodLabel,
        gen_id AS GenreId, gen_label AS GenreLabel,
        cou_id AS CountryId, cou_label AS CountryLabel,
        mus_id AS MusId, mus_title AS MusTitle, mus_duration AS MusDuration
        FROM playlist
        JOIN users USING(use_id)
        JOIN mood ON playlist.MOO_ID = mood.MOO_ID
        JOIN playlist_music USING(play_id)
        JOIN music USING(mus_id)
        JOIN genre_choice USING(play_id)
        JOIN genre USING(gen_id)
        JOIN country_choice USING(play_id)
        JOIN country USING(cou_id)";
        
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
        $sql = "SELECT 
        play_id AS Id, 
        play_title AS Title, 
        play_count AS Count, 
        play_duration AS Duration, 
        play_creation AS Creation,
        play_update AS UpdateAt, 
        play_visibility AS Visibility, 
        use_id AS UserId,
        mood.moo_id AS MoodId, moo_label AS MoodLabel,
        gen_id AS GenreId, gen_label AS GenreLabel,
        cou_id AS CountryId, cou_label AS CountryLabel,
        mus_id AS MusId, mus_title AS MusTitle, mus_duration AS MusDuration
        FROM playlist
        JOIN users USING(use_id)
        JOIN mood ON playlist.MOO_ID = mood.MOO_ID
        JOIN playlist_music USING(play_id)
        JOIN music USING(mus_id)
        JOIN genre_choice USING(play_id)
        JOIN genre USING(gen_id)
        JOIN country_choice USING(play_id)
        JOIN country USING(cou_id) 
        WHERE play_id = :id";
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
    
    public function readAllMoods()
    {
        $sql = "SELECT moo_id AS Id, moo_label AS Label FROM mood";
        try
        {
            if(($this->_req = $this->getDb()->query($sql)) !== false)
            {
                return $this->_req->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    public function readAllGenres()
    {
        $sql = "SELECT gen_id AS Id, gen_label AS Label FROM genre";
        try
        {
            if(($this->_req = $this->getDb()->query($sql)) !== false)
            {
                return $this->_req->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    public function readAllCountries()
    {
        $sql = "SELECT cou_id AS Id, cou_label AS Label FROM country";
        try
        {
            if(($this->_req = $this->getDb()->query($sql)) !== false)
            {
                return $this->_req->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        catch(PDOException $e)
        {
            return $e->getMessage();
        }
    }

    public function searchGenres($query)
    {
        $sql = "SELECT gen_id, gen_label 
                FROM genre 
                WHERE gen_label LIKE :query 
                ORDER BY gen_label 
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
            $sql = "INSERT INTO playlist (play_title, play_duration, play_creation, play_visibility, use_id, moo_id, gen_id, cou_id) 
                    VALUES (:title, :duration, NOW(), :visibility, :use_id, :moo_id, :gen_id, :cou_id)";
            
            $duration = $data['travel_time'];
            
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute([
                ':title' => "Playlist " . date('Y-m-d H:i:s'),
                ':duration' => $duration,
                ':visibility' => 'private',
                ':use_id' => $data['use_id'],
                ':moo_id' => $data['moo_id'],
                ':gen_id' => $data['gen_id'],
                ':cou_id' => $data['cou_id']
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

}

?>
