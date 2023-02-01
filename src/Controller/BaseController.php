<?php

namespace App\Controller;

use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    public function __construct(private VideoRepository $videoRepo) {}

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $videos = $this->videoRepo->findAllVideos(6);

        return $this->render('home/index.html.twig', [
            'videos' => $videos
        ]);
    }
}