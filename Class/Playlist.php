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
    private $mood_id;
    private $genre_id;
    private $country_id;

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

    public function getMoodId()
    {
        return $this->mood_id;
    }

    public function getGenreId()
    {
        return $this->genre_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
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

    public function setMoodId($mood_id)
    {
        $this->mood_id = $mood_id;
    }

    public function setGenreId($genre_id)
    {
        $this->genre_id = $genre_id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

}

?>