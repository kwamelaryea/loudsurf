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

        $algo=$this->get('jhodges.loudsurf.algorithm');
        $algo->addFav($user,$id,$name);

        return $this->redirect( $this->generateUrl('favs') );
    }

    /**
     * @Route("/delete/{id}/", name="delete_fav")
     * @Template()
     */
    public function deleteFavAction(Request $request, $id){
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();

        $algo=$this->get('jhodges.loudsurf.algorithm');
        $algo->deleteFav($user,$id);
 
        return $this->redirect( $this->generateUrl('favs') );
    }


}
