<?php
namespace JHodges\LoudSurfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
* @Route("/users")
**/
class UserController extends Controller{

    /**
     * @Route("/", name="users")
     * @Template()
     */
    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $users=$em->getRepository('JHodgesLoudSurfBundle:User')->findAll();
        return array('users'=>$users);
    }

    /**
     * @Route("/{username}/", name="user")
     * @Template()
     */
    public function userAction($username){
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository('JHodgesLoudSurfBundle:User')->findOneBy(array('username'=>$username));

        $matches=$this->get('jhodges.loudsurf.algorithm')->calculateUserMatches($user);

        return array('user'=>$user,'matches'=>$matches);
    }
}
