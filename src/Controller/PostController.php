<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(PostRepository $postRepo): Response
    {
        //Récupération de tous les articles de la base via la méthode findAll() du repository
        $posts = $postRepo->findAll();

        return $this->render('post/articles.html.twig',[
            'posts' => $posts
        ]);
    }

     /**
     * @Route("/blog-{id}", name="article",requirements={"id"="\d+"})
     */
    public function onePost($id, PostRepository $postRepo): Response
    {
        //Récupération d'un article par son Id via la fonction findOneBy()
        $post = $postRepo->findOneById($id);

        return $this->render('post/article.html.twig',[
            'post' => $post
        ]);
    }
    
}
