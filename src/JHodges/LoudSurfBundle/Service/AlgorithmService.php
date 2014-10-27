<?php
namespace JHodges\LoudSurfBundle\Service;

use Doctrine\ORM\EntityManager;
use JHodges\LoudSurfBundle\Entity\User;
use JHodges\LoudSurfBundle\Service\EchoNestManager;
use JHodges\LoudSurfBundle\Entity\FavSong;

class AlgorithmService{

    private $em=null;
    private $en=null;

    public function __construct(EntityManager $em, EchoNestManager $en){
        $this->em=$em;
        $this->en=$en;
    }

    public function calculateGenreRankings(User $user){

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
        $user->setGenreRankings($rankings);

        $this->em->persist($user);
        $this->em->flush();
    }

    public function calculateUserMatches(User $user){

        //array to store users who also like our genres
        //and a sort index for ordering all matches at the end
        $matches=array();
        $sort_index=array();

        //fetch and loop through all users
        $users=$this->em->getRepository('JHodgesLoudSurfBundle:User')->findAll();
        foreach($users as $user2){
            //keep score
            $score=0;
            //if this user is not us
            if( $user->getId()!=$user2->getId() ){
                //loop through our genre ranks
                foreach($user->getGenreRankings() as $k=>$v){
                    //see if the other user likes the same genre
                    if( $user2->getGenreRanking($k) ){
                        //we like the same genre so increment score
                        $score+=min( $user->getGenreRanking($k) , $user2->getGenreRanking($k) );
                    }
                }
                //genre comparison finished, did we score?
                if($score){
                    //score! so save the results
                    $matches[]=array('user'=>$user2,'score'=>$score);
                    //keep a sort index for sorting by later
                    $sort_index[]=$score;
                }
            }
        }

        //sort and return matches
        array_multisort($sort_index,SORT_DESC,$matches);
        return $matches;
    }


    public function addFav(User $user,$id,$name){
        $data=$this->en->query('song', 'profile', array(
            'id' => $id,
            'bucket'=>array('audio_summary','artist_discovery','artist_discovery_rank','artist_familiarity','artist_familiarity_rank','artist_hotttnesss','artist_hotttnesss_rank','artist_location','song_currency','song_currency_rank','song_hotttnesss','song_hotttnesss_rank','song_type','id:7digital-US','tracks')
        ));

        //create the fav
        $fs=new FavSong();
        $fs->setUser($user);
        $fs->setName($name);
        $fs->setSongId($id);
        $fs->setData($data);
        $this->em->persist($fs);
        $this->em->flush();

        //add to the user so its available before next request
        $user->addFavSong($fs);

        //recalculate rankings
        $this->calculateGenreRankings($user);
        $this->em->persist($user);
        $this->em->flush();
    }    

    public function deleteFav(User $user,$id){
        $favSong=$this->em->getRepository('JHodgesLoudSurfBundle:FavSong')->findOneBy( array('user'=>$user, 'id'=>$id) );

        if($favSong){
            //delete the fav
            $this->em->remove($favSong);
            $this->em->flush();

            //recalculate rankings
            $this->calculateGenreRankings($user);
            $this->em->persist($user);
            $this->em->flush();
        }
        $this->em->flush();        
    }   

}
