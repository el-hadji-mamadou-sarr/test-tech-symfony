<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    /**
     * @Route("/projects/create", name="create_project")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $project = new Project();

        // Create the project form
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            //set the user id
            $user = $this->getUser();
            $project->setMembers([$user->getId()]);

            //enregistrement
            $em->persist($project);
            $em->flush();

            // redirection
            return $this->redirectToRoute('homepage');
        }

        return $this->render('project/create_project.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/projects/{id}", name="project_details")
     */
    public function show(Project $project): Response
    {
        return $this->render('project/details.html.twig', [
            'project' => $project,
        ]);
    }
}
