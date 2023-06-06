<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/projects/{projectId}/tasks/create", name="create_task")
     */
    public function create(Request $request, int $projectId, EntityManagerInterface $em): Response
    {
        // Retrieve the project based on the projectId
        $project = $em->getRepository(Project::class)->find($projectId);

        if (!$project) {
            throw $this->createNotFoundException('Project not found');
        }

        $task = new Task();
        $task->setProject($project);
        $form = $this->createForm(TaskType::class, $task);

        // form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('project_details', ['id' => $projectId]);
        }

        return $this->render('task/create_task.html.twig', [
            'form' => $form->createView(),
            'project' => $project,
        ]);
    }
}
