<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PlatformBundle\Entity\Story;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager()->getRepository('PlatformBundle:Story');

        $listStories = $em->getStories();

        //$usr= $this->getUser()->getUsername();
        

        //var_dump($usr);
        //$story = new Story;


        //$story->setAuthor($usr);


        //$em->persist($story);
        
        return $this->render('PlatformBundle:Default:index.html.twig', array(
            'listStories' => $listStories
        ));
    }

    public function myProfilAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($id);

        return $this->render('UserBundle:Profil:user_profil.html.twig', array(
            'user' => $user
        ));
    }
}
