<?php

namespace App\Controller;

use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    public function __construct(private VideoRepository $videoRepo) {}

    #[Route('/videos', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $limit = $request->query->get('limit');

        $videos = $limit ? $this->videoRepo->findAllVideos($limit) : $this->videoRepo->findAll();

        try {
            return $this->json($videos, Response::HTTP_OK);
        } catch(Error $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/videos', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $existingVideo = $this->videoRepo->findOneBy(['name' => $data['name']]);

        if ($existingVideo) {
            $response['message'] = 'Il y a déjà un film ou une série avec ce titre';
            return $this->json($response, Response::HTTP_CONFLICT);
        }

        try {
            $video = (new Video())
                ->setAuthor($data['author'])
                ->setName($data['name'])
                ->setSynopsis($data['synopsis'])
                ->setType($data['type'])
                ->setGenre($data['genre'])
                ->setReleaseDate(new \DateTime($data['releaseDate']));

            $this->videoRepo->save($video, true);

            return $this->json(['success' => 'Vidéo créée avec succès !'], Response::HTTP_OK);
        } catch(Error $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/videos/{id}', methods: ['GET'])]
    public function show($id): JsonResponse
    {
        $video = $this->videoRepo->find($id);

        if($video === null) {
            throw $this->createNotFoundException();
        }

        try {
            return $this->json($video, Response::HTTP_OK);
        } catch(Error $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}