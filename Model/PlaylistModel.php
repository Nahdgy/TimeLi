<?php

class PlaylistModel extends CoreModel
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

    public function create($data)
    {

    }

    public function addMusics($data)
    {

    }

    public function deleteAll($id)
    {

    }

    public function deleteOne($id)
    {

    }

}

?>
