<?php

class Playlist extends CoreClass
{
    private $play_id;
    private $play_title;
    private $play_count;
    private $play_duration;
    private $play_creation;
    private $play_uptade;
    private $play_visibility;
    private $use_id;
    private $mus_id;
    private $mus_title;
    private $mus_duration;
    private $jou_id;
    private $jou_duration;
    private $dur_id;
    private $dur_rangstart;
    private $dur_rangend;

    public function getId()
    {
        return $this->play_id;
    }   

    public function getTitle()
    {
        return $this->play_title;
    }

    public function getCount()
    {
        return $this->play_count;
    }

    public function getDuration()
    {
        return $this->play_duration;
    }

    public function getCreation()
    {
        return $this->play_creation;
    }

    public function getUptadeAt()
    {
        return $this->play_uptade;
    }

    public function getVisibility()
    {
        return $this->play_visibility;
    }

    public function getUserId()
    {
        return $this->use_id;
    }

    public function getMusId()
    {
        return $this->mus_id;
    }

    public function getMusTitle()
    {
        return $this->mus_title;
    }

    public function getMusDuration()
    {
        return $this->mus_duration;
    }

    public function getJouId()
    {
        return $this->jou_id;
    }

    public function getJouDuration()
    {
        return $this->jou_duration;
    }

    public function getDurId()
    {
        return $this->dur_id;
    }

    public function getDurRangstart()
    {
        return $this->dur_rangstart;
    }

    public function getDurRangend()
    {
        return $this->dur_rangend;
    }

    public function setId($play_id)
    {
        $this->play_id = $play_id;
    }

    public function setTitle($play_title)
    {
        $this->play_title = $play_title;
    }

    public function setCount($play_count)
    {
        $this->play_count = $play_count;
    }

    public function setDuration($play_duration)
    {
        $this->play_duration = $play_duration;
    }

    public function setCreation($play_creation)
    {
        $this->play_creation = $play_creation;
    }

    public function setUptade($play_uptade)
    {
        $this->play_uptade = $play_uptade;
    }

    public function setVisibility($play_visibility)
    {
        $this->play_visibility = $play_visibility;
    }

    public function setUserId($use_id)
    {
        $this->use_id = $use_id;
    }

    public function setMusId($mus_id)
    {
        $this->mus_id = $mus_id;
    }

    public function setMusTitle($mus_title)
    {
        $this->mus_title = $mus_title;
    }

    public function setMusDuration($mus_duration)
    {
        $this->mus_duration = $mus_duration;
    }

    public function setJouId($jou_id)
    {
        $this->jou_id = $jou_id;
    }

    public function setJouDuration($jou_duration)
    {
        $this->jou_duration = $jou_duration;
    }

    public function setDurId($dur_id)
    {
        $this->dur_id = $dur_id;
    }

    public function setDurRangstart($dur_rangstart)
    {
        $this->dur_rangstart = $dur_rangstart;
    }

    public function setDurRangend($dur_rangend)
    {
        $this->dur_rangend = $dur_rangend;
    }

}

?>