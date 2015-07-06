<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Models;

/**
 * Modelo de Películas
 *
 * @author alejandro
 */
class Movie {
    
    private $id;
    private $title;
    private $description;
    private $releaseDate;
    private $director;
    private $duration;
    private $writter;
    private $stars;
    private $actors;
    private $href;
    
    public function getId(){
        return $this->id;
    }
            
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }
    
    public function getTitle() {
        return $this->title;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function setReleaseDate($releaseDate) {
        $this->releaseDate = $releaseDate;
        return $this;
    }
    
    public function getReleaseDate() {
        return $this->releaseDate;
    }
    
    public function setDirector($directorname) {
        $this->director = $directorname;
        return $this;
    }
    
    public function getDirector() {
        return $this->director;
    }
    
    public function setWritter($writter) {
        $this->writter = $writter;
        return $this;
    }
    
    public function getWritter() {
        return $this->writter;
    }
    
    public function setDuration($duration) {
        $this->duration = $duration;
        return $this;
    }
    
    public function getDuration(){
        return $this->duration;
    }

    public function setStars($stars) {
        $this->stars = $stars;
        return $this;
    }
    
    public function getStars() {
        return $this->stars;
    }
    
    public function setActors($actors) {
        $stractors = $actors;
        if(is_array($stractors))
            $stractors = implode (',', $stractors);
        $this->actors = $stractors;
        return $this;
    }
    
    public function getActors() {
        return explode(',', $this->actors);
    }
    
    public function getHref(){
        return '/movies/'.$this->id;
    }
    
    public static function queryableStringProperties() {
        return array('title', 'description', 'director', 'writter');
    }
    
    public static function queryableNumericProperties() {
        return array('duration', 'stars');
    }
    
    public static function queryableDateProperties() {
        return array('releaseDate');
    }
    
}
