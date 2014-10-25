<?php
namespace JHodges\LoudSurfBundle\Service;

use Doctrine\ORM\EntityManager;
use JHodges\LoudSurfBundle\Entity\User;
use JHodges\LoudSurfBundle\Service\EchoNestManager;

class AlgorithmService{

    private $em=null;
    private $en=null;

    public function __construct(EntityManager $em, EchoNestManager $en){
        $this->em=$em;
        $this->en=$en;
    }

    public function calculateGenraRankings(User $user){

        $rankings=array();

        foreach($user->getFavSongs() as $song){
            $songProfile=$this->en->getSongProfile($song->getSongId());
            if($songProfile){
                $artistProfile=$this->en->getArtistProfile($songProfile->artist_id);
                if($artistProfile){
                    foreach($artistProfile->genres as $genre){
                        if( isset($rankings[$genre->name]) ){
                            $rankings[$genre->name]++;
                        }else{
                            $rankings[$genre->name]=1;
                        }
                    }
                }
            }
        }

        arsort($rankings);
        $user->setGenraRankings($rankings);
    }

    public function calculateUserMatches(User $user){

        $matches=array();

        $users=$this->em->getRepository('JHodgesLoudSurfBundle:User')->findAll();
        foreach($users as $user2){
            if( $user->getId()!=$user2->getId() ){
                foreach($user->getGenraRankings() as $k=>$v){
                    if( $user2->getGenraRanking($k) ){
                        $score=min( $user->getGenraRanking($k) , $user2->getGenraRanking($k) );
                        if(isset($matches[$user2->getUsername()])){
                            $matches[$user2->getUsername()]+=$score;
                        }else{
                            $matches[$user2->getUsername()]=$score;
                        }
                    }
                }
            }
        }

        arsort($matches);
        $user->setUserMatches($matches);

    }

}
