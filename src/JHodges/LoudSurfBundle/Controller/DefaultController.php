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

/*
7Digital
oauth_consumer_key=7deehdf7wtbe
oauth_consumer_secret=qx9mu9dmu7ent5f4

To give you a quick start here's an example API request with your key:
http://api.7digital.com/1.2/release/details?releaseid=951886&country=gb&oauth_consumer_key=7deehdf7wtbe

Your affiliate partner ID is: 8340
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

        Echonest::configure('9OYJYBGLH5TXOFNTV');

        $results = Echonest::query('artist', 'profile', array(
            'id' => $id,
            'bucket'=>array('biographies','blogs','discovery','discovery_rank','doc_counts','familiarity','familiarity_rank','genre','hotttnesss','hotttnesss_rank','images','artist_location','news','reviews','songs','terms','urls','video','years_active')
        ));
        return array(
            'artist'=>$results->response->artist,
        );
    }

    /**
     * @Route("/song/{id}", name="song")
     * @Template()
     */
    public function songAction(Request $request, $id){

        Echonest::configure('9OYJYBGLH5TXOFNTV');

        $song = Echonest::query('song', 'profile', array(
            'id' => $id,
            'bucket'=>array('audio_summary','artist_discovery','artist_discovery_rank','artist_familiarity','artist_familiarity_rank','artist_hotttnesss','artist_hotttnesss_rank','artist_location','song_currency','song_currency_rank','song_hotttnesss','song_hotttnesss_rank','song_type','id:7digital-US','tracks')
        ));

        return array(
            'song'=>$song->response->songs[0]
        );
    }


}
