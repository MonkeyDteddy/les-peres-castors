<?php

namespace PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PlatformBundle\Entity\Story;
use PlatformBundle\Entity\Comments;
use PlatformBundle\Form\StoryType;
use PlatformBundle\Form\CommentsType;
use PlatformBundle\Entity\ImageStory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;



class DefaultController extends Controller
{
    
    public function indexAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();

        $listStories = $em->getRepository('PlatformBundle:Story')->getStories();
        
        $stories = $this->get('knp_paginator')
            ->paginate(
                $listStories,
                $request->query->get('page', 1),
                $request->query->get('limit', 4)
            );
            //return var_dump($stories);
        return $this->render('PlatformBundle:Default:index.html.twig', array(
            'stories' => $stories
        ));
    }

    public function viewStoryAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $story = $em->getRepository('PlatformBundle:Story')->find($id);

        
        $comments = new Comments();

        $formComment = $this->get('form.factory')->create(CommentsType::class, $comments);

        $listComments = $em->getRepository('PlatformBundle:Comments')
            ->findBy(array('story' => $story));

        //$nbrComments = $listComments->getNbrComments();
        
        //var_dump($nbrComments);

        if($this->getUser() !== null) {
            $user = $this->getUser()->getUsername();
        }  else {
            $user = null;
        }
        if($request->isMethod('POST') && $formComment->handleRequest($request)->isValid())
        {
            if(null === $user)
            {
                return $this->redirectToRoute('platform_homepage'); 
            }

            $comments->setStory($story);

            $comments->setAuthor($user);


            $em->persist($comments);

            $em->flush();

            return $this->redirectToRoute('view_story', array('id' => $id)); 
        }

        if($story === null)
        {
            //mettre page 301
            throw new NotFoundHttpException("L'histoire " . $id . "n'éxiste pas");
            
        }


        return $this->render('PlatformBundle:story:story.html.twig', array(
            'story' => $story,
            'user' => $user,
            'listComments' => $listComments,
            'formComment' => $formComment->createView()
        ));
    }

    public function addStoryAction(Request $request)
    {
        
        if($this->getUser() === null) {
            return $this->redirectToRoute('platform_homepage');
        }


        $em = $this->getDoctrine()->getManager();
        $usrId = $this->getUser()->getId();
        $user = $em->getRepository('UserBundle:User')->find($usrId);

        $usr = $this->getUser()->getUsername();
        
        
        $story = new Story();
        //$imageStory = new ImageStory();

    
        $form = $this->get('form.factory')->create(StoryType::class, $story);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            
 
            $story->setAuthor($usr);

            $story->setUser($user);
            
            $em->persist($story);
            $em->flush();
 
            $request->getSession()->getFlashBag()->add('notice','Histoire bien enregistrée.');
 
            $listStories = $em->getRepository('PlatformBundle:Story')->getStories();
                 return $this->redirectToRoute('platform_homepage', array(
                 'listStories' => $listStories
            ));
         }
        
        

            return $this->render('PlatformBundle:story:add_story.html.twig', array(
                'form' => $form->createView(),
        ));
        
        
    }

    public function editStoryAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $story = $em->getRepository('PlatformBundle:Story')->find($id);

        $storyAuthor = $story->getAuthor();


        
        if($this->getUser() === null || $storyAuthor !== $this->getUser()->getUsername()) {
            return $this->redirectToRoute('platform_homepage');
        }

        $form = $this->get('form.factory')->create(StoryType::class, $story);
        if (null === $story) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            //$story->getImageStory()->upload();
            $em->flush();

            return $this->redirectToRoute('view_story', array('id' => $story->getId()));
        }

        return $this->render('PlatformBundle:story:edit_story.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function removeStoryAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $story = $em->getRepository('PlatformBundle:Story')->find($id);

        $storyAuthor = $story->getAuthor();
        if($this->getUser() === null || $storyAuthor !== $this->getUser()->getUsername()) {
            return $this->redirectToRoute('platform_homepage');
        }

        $em->remove($story);
        $em->flush();

        return $this->redirectToRoute('platform_homepage');
    }

    public function menuCategoriesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $listCategories = $em->getRepository('PlatformBundle:Category')->findAll();
        return $this->render('PlatformBundle:Menu:menu_categories.html.twig', array(
            'listCategories' => $listCategories
        ));
    }

    public function viewCategoryAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('PlatformBundle:Category')->find($id);
        $listStories = $em->getRepository('PlatformBundle:Story')
            ->findBy(array('category' => $category));

        $listStories = $this->get('knp_paginator')
            ->paginate(
                $listStories,
                $request->query->get('page', 1),
                $request->query->get('limit', 4)
            );
        //$listCategories = $em->getRepository('PlatformBundle:Category')->findAll();
        return $this->render('PlatformBundle:Category:view_category.html.twig', array(
            'listStories' => $listStories,
            'category'    => $category
        ));
    }

    public function viewMyStoriesAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('UserBundle:User')->find($id);

        $listStories = $em->getRepository('PlatformBundle:Story')
            ->findBy(array('user' => $user));

        $listStories = $this->get('knp_paginator')
            ->paginate(
                $listStories,
                $request->query->get('page', 1),
                $request->query->get('limit', 4)
            );
        
        if($this->getUser()->getUsername() != $user->getUsername())
        {
            return $this->redirectToRoute('platform_homepage');
        }

        return $this->render('PlatformBundle:story:my_stories.html.twig', array(
            'listStories' => $listStories
        ));

    }
    public function menuProfilAction()
    {
        
        return $this->render('PlatformBundle:Menu:menu_profil.html.twig');
    }
}
