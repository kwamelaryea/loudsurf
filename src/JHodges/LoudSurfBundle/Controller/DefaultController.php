<?php
namespace JHodges\LoudSurfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Echonest\Service\Echonest;
/*
User: kwamelaryea
PW: u8ARlEWnxI2A

Your API Key: 9OYJYBGLH5TXOFNTV 
Your Consumer Key: 63e0eee012643e7e26ed7754d28803d0 
Your Shared Secret: J2zIhoy9QxGL6yST3AiqoA
*/


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

		Echonest::configure('9OYJYBGLH5TXOFNTV');

		$results = Echonest::query('song', 'search', array(
		    'combined' => $q
		));

        return array('results'=>$results->response->songs);
    }

    /**
     * @Route("/artist/{id}", name="artist")
     * @Template()
     */
    public function artistAction(Request $request, $id){

        Echonest::configure('9OYJYBGLH5TXOFNTV');

        $profile = Echonest::query('artist', 'profile', array(
            'id' => $id
        ));
        $bio = Echonest::query('artist', 'biographies', array(
            'id' => $id
        ));
        return array(
            'profile'=>$profile->response->artist,
            'bios'=>$bio->response->biographies
        );
    }

    /**
     * @Route("/song/{id}", name="song")
     * @Template()
     */
    public function songAction(Request $request, $id){

        Echonest::configure('9OYJYBGLH5TXOFNTV');

        $results = Echonest::query('song', 'profile', array(
            'id' => $id
        ));

        return array('song'=>$results);
    }


}
