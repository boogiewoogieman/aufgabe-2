<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController {

  #[Route('/ticket/list', name: 'app_ticket_list', methods: 'GET')]
  public function list(): JsonResponse {
    // todo: implement

    return $this->json([]);
  }

  #[Route('/ticket/create', name: 'app_ticket_create', methods: 'POST')]
  public function create(): JsonResponse {
    // todo: implement

    return $this->json([]);
  }

  #[Route('/ticket/{id}', name: 'app_ticket_show', methods: 'GET')]
  public function show(int $id): JsonResponse {
    // todo: implement

    return $this->json([]);
  }

}
