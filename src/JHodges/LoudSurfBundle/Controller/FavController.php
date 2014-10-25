<?php
namespace JHodges\LoudSurfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Echonest\Service\Echonest;

use JHodges\LoudSurfBundle\Entity\FavSong;

/**
 * @Route("/favs", name="favs")
*/
class FavController extends Controller{
    /**
     * @Route("/", name="favs")
     * @Template()
     */
    public function indexAction(){
    	$securityContext = $this->get('security.context');
		$user = $securityContext->getToken()->getUser();
		$em=$this->getDoctrine()->getManager();

		$favSongs=$em->getRepository('JHodgesLoudSurfBundle:FavSong')->findBy( array('user'=>$user) );

        return array('favSongs'=>$favSongs);
    }

    /**
     * @Route("/add/{id}/{name}/", name="add_fav")
     * @Template()
     */
    public function addFavAction(Request $request, $id, $name){
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $en=$this->get('jhodges.echonest');

        $data= $en->query('song', 'profile', array(
            'id' => $id,
            'bucket'=>array('audio_summary','artist_discovery','artist_discovery_rank','artist_familiarity','artist_familiarity_rank','artist_hotttnesss','artist_hotttnesss_rank','artist_location','song_currency','song_currency_rank','song_hotttnesss','song_hotttnesss_rank','song_type','id:7digital-US','tracks')
        ));

        //create the fav
        $fs=new FavSong();
        $fs->setUser($user);
        $fs->setName($name);
        $fs->setSongId($id);
        $fs->setData($data);
        $em->persist($fs);
        $em->flush();

        //recalculate rankings
        $this->get('jhodges.loudsurf.algorithm')->calculateRankings($user);
        $this->get('jhodges.loudsurf.algorithm')->calculateMatches($user);
        $em->persist($user);
        $em->flush();

        return $this->redirect( $this->generateUrl('favs') );
    }

    /**
     * @Route("/delete/{id}/", name="delete_fav")
     * @Template()
     */
    public function deleteFavAction(Request $request, $id){
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();

        $em=$this->getDoctrine()->getManager();
        $favSong=$em->getRepository('JHodgesLoudSurfBundle:FavSong')->findOneBy( array('user'=>$user, 'id'=>$id) );

        if($favSong){
            //delete the fav
            $em->remove($favSong);
            $em->flush();

            //recalculate rankings
            $this->get('jhodges.loudsurf.algorithm')->calculateRankings($user);
            $this->get('jhodges.loudsurf.algorithm')->calculateMatches($user);
            $em->persist($user);
            $em->flush();
        }

        return $this->redirect( $this->generateUrl('favs') );
    }


}
