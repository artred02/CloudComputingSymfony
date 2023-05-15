<?php

namespace App\Controller;

use App\Entity\ForumPost;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager->getRepository(ForumPost::class)->findAll();
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'posts' => $posts
        ]);
    }

    #[Route('/add', name: 'app_form')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $post = new ForumPost();

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post->setDate(new \DateTime());
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute('app_main');
        }

        return $this->render('main/add.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/post/{postId}', name: 'app_post')]
    public function post(EntityManagerInterface $entityManager, int $postId): Response
    {
        $post = $entityManager->getRepository(ForumPost::class)->findOneBy(['id' => $postId]);

        return $this->render('main/post.html.twig', [
            'controller_name' => 'MainController',
            'post' => $post
        ]);
    }
}
