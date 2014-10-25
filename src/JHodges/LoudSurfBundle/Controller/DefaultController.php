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
     * @Route("/recalc/", name="recalc")
     * @Template()
     */
    public function recalcAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT u
            FROM JHodgesLoudSurfBundle:User u
        ');
        $users = $query->getResult();

        //recalculate rankings
        foreach($users as $user){
            $this->get('jhodges.loudsurf.algorithm')->calculateGenraRankings($user);
            $this->get('jhodges.loudsurf.algorithm')->calculateUserMatches($user);
            $em->persist($user);
        }

        $em->flush();

        die("DONE");
    }


}
