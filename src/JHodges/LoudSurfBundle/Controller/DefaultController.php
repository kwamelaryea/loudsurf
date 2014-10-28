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
            'sort' => 'song_hotttnesss-desc',
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
     * @Route("/matches/", name="matches")
     * @Template()
     *
     * NOTE: This is a dev action only. Will take a long time to process with many users. 
     *
     */
    public function matchesAction(Request $request){

	set_time_limit(600);

        $en=$this->get('jhodges.echonest');
        $algo=$this->get('jhodges.loudsurf.algorithm');
        $em = $this->get('doctrine')->getManager();

        $allMatches=array();
        $sortIndex=array();

        $users=$em->getRepository('JHodgesLoudSurfBundle:User')->findAll();
        foreach($users as $user){
            $matches=$algo->calculateUserMatches($user);
            foreach($matches as $match){
                if($user->getId() > $match['user']->getId()){
                    $allMatches[]=array(
                        'user'=>$user,
                        'match'=>$match
                    );
                    $sortIndex[]=$match['score_per'];
                }
            }
        }

        array_multisort($sortIndex,SORT_DESC,$allMatches);

        return array(
            'matches'=>$allMatches
        );
    }

}
