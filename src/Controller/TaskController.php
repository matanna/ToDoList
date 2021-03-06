<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Utils\ControlAuthor;
use App\Utils\TaskRemoveAuthorization;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction(ControlAuthor $controlAuthor, CacheInterface $cache)
    {
        $tasks = $this->getDoctrine()->getRepository('App:Task')->findAll();

        foreach ($tasks as $task) {
            $controlAuthor->controlTaskAuthor($task);
        }

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request, UserInterface $user)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();

            $task->setUser($user);

            $manager->persist($task);
            $manager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request, ControlAuthor $controlAuthor)
    {
        $controlAuthor->controlTaskAuthor($task);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        if ($task->isDone() === false) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        } else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non terminée.', $task->getTitle()));
        }
        
        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task, TaskRemoveAuthorization $taskRemoveAuthorization)
    {
        $this->getUser();

        $removeIsOk = $taskRemoveAuthorization->taskRemove($task);

        if ($removeIsOk) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($task);
            $manager->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
            return $this->redirectToRoute('task_list');
        }

        $this->addFlash('error', 'Vous n\'êtes pas autorisé à supprimer cette tâche !');
        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/isDone", name="task_isDone")
     */
    public function tasksIsDoneAction(ControlAuthor $controlAuthor)
    {
        $tasks = $this->getDoctrine()->getRepository('App:Task')->findBy(['isDone' => 1]);

        foreach ($tasks as $task) {
            $controlAuthor->controlTaskAuthor($task);
        }

        return $this->render('task/tasksIsDone.html.twig', ['tasks' => $tasks]);
    }
}
