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



class CommentsController extends Controller
{
    
    public function removeCommentAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('PlatformBundle:Comments')->find($id);

        $commentAuthor = $comment->getAuthor();

        if($this->getUser() === null || $commentAuthor !== $this->getUser()->getUsername())
        {
            return $this->redirectToRoute('platform_homepage');
        }

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('view_story', array(
            'id' => $comment->getStory()->getId()
        ));
    }
}