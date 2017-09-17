<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(BlogPost::class);
        $blogpostPublished = $repository->findBlogPostPublished();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'blogpostsPublished' => $blogpostPublished
        ]);
    }
}
