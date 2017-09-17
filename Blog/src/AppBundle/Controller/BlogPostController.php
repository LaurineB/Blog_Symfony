<?php
/**
 * Created by PhpStorm.
 * User: labai
 * Date: 13/04/2017
 * Time: 10:23
 */

namespace AppBundle\Controller;

use AppBundle\Entity\BlogPost;

use AppBundle\Entity\Comment;
use AppBundle\Form\Type\BlogPostType;

use AppBundle\Form\Type\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class BlogPostController extends Controller
{
    /**
     * @Route("/posts", name="posts")
     */
    public function blogPostList()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $repository = $em->getRepository(BlogPost::class);

        $blogpostPublished = $repository->findBlogPostPublished();
        $blogpostNotPublished = $repository->findBlogPostNotPublished();

        // replace this example code with whatever you need
        return $this->render('blogpost/list.html.twig', [
            'blogpostsPublished' => $blogpostPublished, 'blogpostsNotPublished' => $blogpostNotPublished
        ]);
    }

    /**
     * @Route("/admin/posts/create", name="postsCreate")
     */
    public function blogPostCreate(Request $request)
    {
        $blogpost = new BlogPost();
        $form = $this->createForm(BlogPostType::class,$blogpost);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $blogpost->setPublishedAt(null);

            // $file stores the uploaded PDF file
            $file = $blogpost->getCover();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('covers_directory'),
                $fileName
            );

            // Update the 'cover' property to store the img file name
            // instead of its contents
            $blogpost->setCover($fileName);


            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($blogpost); // persist is used when the object is nit yet in database
            $em->flush(); // execute query

            $flash = sprintf("L'article %s a bien été soumis",$blogpost->getTitle());
            $this->addFlash('success',$flash);

            return $this->redirectToRoute('posts');
        }
        return $this->render("blogpost/create.html.twig",['form' => $form->createView()]);
    }

    /**
     * @Route("/posts/{id}", name="postsShow")
     */
    public function blogPostShow(Request $request,BlogPost $blogPost)
    {
        /*Gestion du formulaire du commentaire */
        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $comment->setPublishedAt(new \DateTime());
            $em = $this->get('doctrine.orm.entity_manager');
            $comment->setBlogpost($blogPost);
            $em->persist($comment); // persist is used when the object is nit yet in database
            $em->flush(); // execute query

            $flash = sprintf("Le commentaire %s a bien été soumis",$comment->getTitle());
            $this->addFlash('success',$flash);

            $listComments = $blogPost->getComments();
        }

        $listComments = $blogPost->getComments();

        return $this->render('blogpost/show.html.twig',[ 'post' => $blogPost,'comments'=>$listComments,'form' => $form->createView() ] );
    }

    /**
     *@Route("/admin/posts/edit/{id}", name="postsEdit")
     */
    public function blogPostEdit(BlogPost $blogPost,Request $request)
    {
        $form = $this->createForm(BlogPostType::class,$blogPost);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->flush(); // execute query

            $flash = sprintf("L'article %s a bien été modifié",$blogPost->getTitle());
            $this->addFlash('success',$flash);

            return $this->redirectToRoute('postsEdit', ['id' => $blogPost->getId()]);
        }

        return $this->render('blogpost/edit.html.twig',['post' => $blogPost]);
    }

    /**
     * @Route("/admin/posts/publish/{id}", name="postsPublish")
     */
    public function publishPost(BlogPost $blogPost)
    {
        if($blogPost->getPublishedAt() == null){
            $blogPost->setPublishedAt(new \DateTime());
            $datetime = new \DateTime();
            $flash = sprintf("Article publié");
            $this->addFlash('success',$flash);
        } else {
            $flash = sprintf("Article déjà publié");
            $this->addFlash('error',$flash);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $em->flush(); // execute query
        return $this->redirectToRoute('postsShow',['id'=>$blogPost->getId()]);
    }

    /**
     * @Route("/admin/posts/delete/{id}", name="postsDelete")
     */
    public function deletePost(BlogPost $blogPost)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $coverFilename = $blogPost->getCover();
        $fs = new Filesystem();
        $fs->remove($this->get('kernel')->getRootDir().'/../web/img/cover/'.$coverFilename);

        $em->remove($blogPost);
        $em->flush();

        return $this->redirectToRoute('posts');
    }
}
