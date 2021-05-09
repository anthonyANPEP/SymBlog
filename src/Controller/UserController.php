<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
    * Methode pour creer ou editer un post
    * @Route("/user/new", name="article_new")
    * @Route("/user/edit-{id}", name="article_edit",requirements={"id"="\d+"})
     *@Security("is_granted('ROLE_USER')")
    */
   public function addPost($id = null,Request $request,EntityManagerInterface $manager ,PostRepository $postRepo):Response
   {
       $post = $postRepo->findOneById($id);

       if(!$post){
           $post = new Post();
       }

       //On demande a symfony de récupérer le formulaire et de l'associer à notre Post
       $form = $this->createForm(PostType::class, $post);

       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid() ) {
            //On récupère l'utilisateur connecté
            $user = $this->getUser();

            if(!$post->getId()){

                foreach($post->getImages() as $key => $imagePost){
    
                    $image = $form['images'][$key]['url']->getData();
                    
                    $fileName = md5(uniqid()).'.'.$image->guessExtension(); 
                    $image->move($this->getParameter('images_directory'), $fileName); 
                    $imagePost->setUrl($fileName); 
    
                    $imagePost->setPost($post);
                }
            }

            if ($post->getUser() === null){
                //On associe l'utilisateur connecté et le post en cour de création
                $post->setUser($user);
            }
          
           $manager->persist($post);

           $manager->flush();

           //Redirection vers le post crée après l'envoi du nouveau post
           return $this->redirectToRoute('article',['id' => $post->getId()]);
       }


       return $this->render('user/new.html.twig',[
           'form' => $form->createView(),
           'edit' => $post->getId() !== null
       ]);
   }

   /**
    * @Security("is_granted('ROLE_USER') and user === post.getUser()", message="Ce post ne vous appartient pas, vous ne pouvez pas le supprimer")
    * @Route("/user/delete-{id}",name="article_delete",requirements={"id"="\d+"})
    */
   public function delPost($id,PostRepository $postRepo,EntityManagerInterface $manager){

       $post = $postRepo->findOneById($id);

       $manager->remove($post);

       $manager->flush();

       return $this->redirectToRoute('blog');
   }
   /**
    * @Route("/user/articles", name="articles_user")
    */
    public function getPostByUser(PostRepository $postRepo)
{
        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        //On recherche les posts correspondant à cet utilisateur
        $posts = $postRepo->findByUser($user);

        return $this->render('user/articles.html.twig',[
            'posts' => $posts,
            'user' => $user
        ]);

    
}
}
