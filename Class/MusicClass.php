<?php

class MusicClass extends CoreClass
{
    private $mus_id;
    private $mus_title;
    private $mus_release;
    private $mus_duration;
    private $mus_rating;
    private $mus_link;
    private $album_id;
    private $artist_id;
    private $mood_id;
    private $type_id;
    private $country_id;
    private $spotify_id;


    // Getters
    public function getId()
    {
        return $this->mus_id;
    }

    public function getTitle()
    {
        return $this->mus_title;
    }

    public function getRelease()
    {
        return $this->mus_release;
    }

    public function getDuration()
    {
        return $this->mus_duration;
    }

    public function getRating()
    {
        return $this->mus_rating;
    }

    public function getLink()
    {
        return $this->mus_link;
    }

    public function getAlbumId()
    {
        return $this->album_id;
    }

    public function getArtistId()
    {
        return $this->artist_id;
    }

    public function getMoodId()
    {
        return $this->mood_id;
    }

    public function getTypeId()
    {
        return $this->type_id;
    }

    public function getCountryId()
    {
        return $this->country_id;
    }

    public function getSpotifyId()
    {
        return $this->spotify_id;
    }

    // Setters
    public function setId($id)
    {
        $this->mus_id = $id;
    }

    public function setTitle($title)
    {
        $this->mus_title = $title;
    }

    public function setRelease($release)
    {
        $this->mus_release = $release;
    }

    public function setDuration($duration)
    {
        $this->mus_duration = $duration;
    }

    public function setRating($rating)
    {
        $this->mus_rating = $rating;
    }

    public function setLink($link)
    {
        $this->mus_link = $link;
    }

    public function setAlbumId($album_id)
    {
        $this->album_id = $album_id;
    }

    public function setArtistId($artist_id)
    {
        $this->artist_id = $artist_id;
    }

    public function setMoodId($mood_id)
    {
        $this->mood_id = $mood_id;
    }

    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
    }

    public function setCountryId($country_id)
    {
        $this->country_id = $country_id;
    }

    public function setSpotifyId($spotify_id)
    {
        $this->spotify_id = $spotify_id;
    }
    
}