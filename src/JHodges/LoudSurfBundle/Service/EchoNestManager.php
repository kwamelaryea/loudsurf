<?php
namespace JHodges\LoudSurfBundle\Service;

use Echonest\Service\Echonest;

class EchoNestManager{

    private $cache;

    public function __construct($cache,$apiKey){
        $this->cache=$cache;
        Echonest::configure($apiKey);
    }

    public function query($name,$method,$data){
        $id=$name.'.'.$method.'.'.md5(print_r($data,true));
        if($this->cache->contains($id)){
            return $this->cache->fetch($id);
        }else{
            $result=Echonest::query($name,$method,$data);
            $this->cache->save($id,$result);
            return $result;
        }
    }

    public function getSongProfile($id){
        $results = $this->query('song', 'profile', array(
            'id' => $id,
            'bucket'=>array('audio_summary','artist_discovery','artist_discovery_rank','artist_familiarity','artist_familiarity_rank','artist_hotttnesss','artist_hotttnesss_rank','artist_location','song_currency','song_currency_rank','song_hotttnesss','song_hotttnesss_rank','song_type','id:7digital-US','tracks')
        ));

        if( isset($results->response->songs[0]) ){
            return $results->response->songs[0];
        }

        return false;
    }

    public function getArtistProfile($id){
        $results = $this->query('artist', 'profile', array(
            'id' => $id,
            'bucket'=>array('biographies','blogs','discovery','discovery_rank','doc_counts','familiarity','familiarity_rank','genre','hotttnesss','hotttnesss_rank','images','artist_location','news','reviews','songs','terms','urls','video','years_active')
        ));

        if( isset($results->response->artist) ){
            return $results->response->artist;
        }

        return false;      
    }

}