<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin")
     */
    public function getUsers(UserRepository $userRepo, CategoryRepository $catRepo): Response
    {
        $users = $userRepo->findAll();

        $cats = $catRepo->findAll();

        return $this->render('admin/admin.html.twig', [
            'users' => $users,
            'cats' => $cats
        ]);
    }
    /**
     * @Route("/admin/posts", name="admin_posts")
     */
    public function getPosts(PostRepository $postRepo): Response
    {
        $posts = $postRepo->findAll();

        return $this->render('admin/posts.html.twig', [
            'posts' => $posts,
        ]);
    }
    /**
     * @Route("/admin/categorie", name="category_new")
     * @Route("/admin/categorie-{id}", name="category_edit",requirements={"id"="\d+"})
     */
    public function addCategory($id = null,Request $request,EntityManagerInterface $manager ,CategoryRepository $catRepo): Response
    {
        $category = $catRepo->findOneById($id);
       
       if(!$category){
           $category = new Category();
       }

       //On demande a symfony de récupérer le formulaire et de l'associer à notre Category
       $form = $this->createForm(CategoryType::class, $category);


       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid() ) {
          
           $manager->persist($category);

           $manager->flush();

           //Redirection vers la catégorie crée après l'envoi d'une nouvelle categorie
           return $this->redirectToRoute('admin',['id' => $category->getId()]);
       }


       return $this->render('admin/newCategory.html.twig',[
           'form' => $form->createView(),
           'edit' => $category->getId() !== null
       ]);
    }

    /**
     * @Route("/admin/category/delete-{id}", name="cat_delete",requirements={"id"="\d+"})
     */
    public function delCategory($id,CategoryRepository $catRepo,EntityManagerInterface $manager){

        $category = $catRepo->findOneById($id);
 
        $manager->remove($category);
 
        $manager->flush();
 
        return $this->redirectToRoute('admin');
}
    /**
     * @Route("/admin/user-{id}", name="user_delete",requirements={"id"="\d+"})
     */
    public function delUser($id,UserRepository $userRepo,EntityManagerInterface $manager)
    {
        
       $user = $userRepo->findOneById($id);

       $manager->remove($user);

       $manager->flush();

       return $this->redirectToRoute('admin');
    }
}
