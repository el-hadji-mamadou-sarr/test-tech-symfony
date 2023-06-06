<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $projectIds = $this->getUser()->getProjects();


        $projects = $em
            ->getRepository(Project::class)
            ->findBy(['id' => $projectIds]);

        // Extract the names from the projects
        $projectNames = [];
        foreach ($projects as $project) {
            $projectNames[] = $project->getName();
        }

        return $this->render('home/index.html.twig', [
            'projectNames' => $projectNames,
        ]);
    }
}
