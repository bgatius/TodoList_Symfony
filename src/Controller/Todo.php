<?php

// src/Controller/Todo.php
namespace App\Controller;

use App\Repository\TodoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Todo extends AbstractController
{

    /**
     * @Route("/todo/index")
     */
    public function todo(TodoRepository $todoRepository): Response
    {
        return $this->render('todo/index.html.twig', [
            'todos' => $todoRepository->findAll()
        ]);
    }

    /**
     * @Route("/todo/supprimer")
     */
    public function delete(TodoRepository $todoRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $allCompleted = $todoRepository->findBy(['completed' => true]);

        // dump($allCompleted); exit;
        foreach ($allCompleted as $complete) {
            $em->remove($complete);
            $em->flush();
        }

        return $this->render('todo/index.html.twig', [
            'todos' => $todoRepository->findAll()
        ]);
    }
}
