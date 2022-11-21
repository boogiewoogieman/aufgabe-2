<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController {

  #[Route('/event/list', name: 'app_event_list', methods: 'GET')]
  public function list(): JsonResponse {
    // todo: implement

    return $this->json([]);
  }

  #[Route('/event/create', name: 'app_event_create', methods: 'POST')]
  public function create(): JsonResponse {
    // todo: implement

    return $this->json([]);
  }

  #[Route('/event/{id}', name: 'app_event_show', methods: 'GET')]
  public function show(int $id): JsonResponse {
    // todo: implement

    return $this->json([]);
  }

}
