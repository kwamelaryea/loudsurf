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
 * @Route("/favs/", name="favs")
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
     * @Route("add/{id}/{name}/", name="add_fav")
     * @Template()
     */
    public function addFavAction(Request $request, $id, $name){
    	$securityContext = $this->get('security.context');
		$user = $securityContext->getToken()->getUser();
		$em=$this->getDoctrine()->getManager();

    	$fs=new FavSong();
    	$fs->setUser($user);
    	$fs->setName($name);
    	$fs->setSongId($id);

    	$em->persist($fs);
    	$em->flush();

    	return $this->redirect( $this->generateUrl('favs') );
    }

}
