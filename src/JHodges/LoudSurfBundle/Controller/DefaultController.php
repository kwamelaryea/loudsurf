<?php
namespace JHodges\LoudSurfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Echonest\Service\Echonest;

class DefaultController extends Controller{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction(){
        return array();
    }

    /**
     * @Route("/search/", name="search")
     * @Template()
     */
    public function searchAction(Request $request){

    	$q=$request->get('q');

		$en=$this->get('jhodges.echonest');

		$results = $en->query('song', 'search', array(
		    'combined' => $q,
            'bucket'=>array('id:7digital-US','audio_summary','tracks')
		));

        return array('results'=>$results->response->songs);
    }

    /**
     * @Route("/artist/{id}", name="artist")
     * @Template()
     */
    public function artistAction(Request $request, $id){

        $en=$this->get('jhodges.echonest');

        return array(
            'artist'=>$en->getArtistProfile($id)
        );
    }

    /**
     * @Route("/song/{id}", name="song")
     * @Template()
     */
    public function songAction(Request $request, $id){

        $en=$this->get('jhodges.echonest');

        return array(
            'song'=>$en->getSongProfile($id)
        );
    }

    /**
     * @Route("/test/", name="test")
     * @Template()
     */
    public function testAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT u
            FROM JHodgesLoudSurfBundle:User u
        ');
        $users = $query->getResult();

        $rank=array();

        $en=$this->get('jhodges.echonest');

        //calc rankings for genras of each user
        foreach($users as $user){
            foreach($user->getFavSongs() as $song){
                $songProfile=$en->getSongProfile($song->getSongId());
                if($songProfile){
                    $artistProfile=$en->getArtistProfile($songProfile->artist_id);
                    if($artistProfile){
                        foreach($artistProfile->genres as $genre){
                            if( isset($rank[$user->getId()][$genre->name]) ){
                                $rank[$user->getId()][$genre->name]++;
                            }else{
                                $rank[$user->getId()][$genre->name]=1;
                            }
                        }
                    }
                }
            }
        }

        //compare each user to every other user
        $match=array();
        foreach($users as $user1){
            foreach($users as $user2){
                if(isset($rank[$user1->getId()]) && isset($rank[$user2->getId()])){
                    foreach($rank[$user1->getId()] as $k=>$v){
                        if( isset( $rank[$user2->getId()][$k] ) ){
                            $score=min($rank[$user2->getId()][$k] , $rank[$user1->getId()][$k]);
                            if(isset($match[$user1->getId()][$user2->getUsername()])){
                                $match[$user1->getId()][$user2->getUsername()]+=$score;
                            }else{
                                $match[$user1->getId()][$user2->getUsername()]=$score;
                            }
                        }
                    }
                }
            }
        }

        return array(
            'users'=>$users,
            'rank'=>$rank,
            'match'=>$match
        );
    }


}
