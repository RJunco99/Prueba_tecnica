<?php

namespace App\Controller;

use App\Repository\PodcastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(PodcastRepository $podcastRepository): Response
    {
        $podcasts = $podcastRepository->findAll();
        return $this->render('home.html.twig', [
            'podcasts' => $podcasts,
        ]);
    }
}
