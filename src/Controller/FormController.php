<?php
// src/Controller/FormController.php
namespace App\Controller;

// use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Repository\TodoRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TodoType;
use App\Entity\Todo;

class FormController extends AbstractController
{
    /**
     * @Route("/form/new")
     */
    public function new(Request $request): Response
    {
        $todo = new Todo();
        $todo->setLabel('');
        $todo->setCompleted(false);

        $form = $this->createForm(TodoType::class, $todo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();

            // var_dump($todo); exit;

            $insert = $this->getDoctrine()->getManager();

            $insert->persist($todo);
            $insert->flush();

            return $this->redirectToRoute('app_todo_todo');
        }

        return $this->renderForm('todo/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/form/edit/{id}")
     */
    public function update(TodoRepository $todoRepository, Request $request, int $id): Response
    {
        $task = $todoRepository->find($id);

        if (!$task) {
            throw $this->createNotFoundException(
                'No task found for id '.$id
            );
        }

        $task->setLabel($task->getLabel());
        $task->setCompleted($task->getCompleted());

        $form = $this->createForm(TodoType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            // var_dump($todo); exit;

            $insert = $this->getDoctrine()->getManager();

            $insert->persist($task);
            $insert->flush();

            return $this->redirectToRoute('app_todo_todo');
        }

        return $this->renderForm('todo/update.html.twig', [
            'form' => $form,
        ]);
    }
}