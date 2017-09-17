<?php
/**
 * Created by PhpStorm.
 * User: lb
 * Date: 16/04/2017
 * Time: 18:45
 */

namespace AppBundle\Controller;


use AppBundle\Entity\BlogPost;
use AppBundle\Entity\Comment;
use AppBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class CommentController extends Controller
{
    /**
    * @Route("/comment/{id}", name="comment")
    */
    public function commentBlogPost(Request $request, BlogPost $blogPost)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $comment->setPublishedAt(new \DateTime());
            $em = $this->get('doctrine.orm.entity_manager');
            $comment->setBlogpost($blogPost);
            $em->persist($comment); // persist is used when the object is nit yet in database
            $em->flush(); // execute query

            $flash = sprintf("L'article %s a bien été soumis",$comment->getTitle());
            $this->addFlash('success',$flash);

            unset($comment);
            unset($form);

            $listComments = $blogPost->getComments();

            return $this->render('blogpost/show.html.twig',['post' => $blogPost,'comments'=>$listComments]);
        }
        return $this->render("comment/create.html.twig",['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/comment/delete/{id}", name="commentDelete")
     */
    public function deletePost(Comment $comment)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($comment);
        $em->flush();

        $flash = sprintf("Le commentaire %s a bien été supprimé",$comment->getTitle());
        $this->addFlash('success',$flash);

        $post = $comment->getBlogpost();
        $idPost = $post->getId();

        return $this->redirectToRoute('postsShow',['id'=>$idPost]);
    }
}