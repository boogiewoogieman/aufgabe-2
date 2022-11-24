<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TicketController extends AbstractController {

  #[Route('/ticket/list', name: 'app_ticket_list', methods: 'GET')]
  public function list(TicketRepository $ticketRepository): JsonResponse {
    $tickets = $ticketRepository->findAll();
    try {
      return $this->json([
        'result' => array_map(function ($ticket) {
          return [
            ...$ticket->toArray(),
            'barcodeImage' => base64_encode($ticket->generateBarcodeImage()),
          ];
        }, $tickets),
      ]);
    } catch (Exception $e) {
      return $this->json(['error' => $e->getMessage()], 500);
    }
  }

  #[Route('/ticket/create', name: 'app_ticket_create', methods: 'POST')]
  public function create(Request $request, TicketRepository $ticketRepository, EventRepository $eventRepository, ValidatorInterface $validator): JsonResponse {
    $data = json_decode($request->getContent(), TRUE);

    if (!empty($data['eventId'])) {
      $data['event'] = $eventRepository->find($data['eventId']);
    }

    $ticket = Ticket::fromArray($data);
    $errors = $validator->validate($ticket);
    if (count($errors)) {
      return $this->json(['error' => $errors[0]->getMessage()]);
    }

    $ticketRepository->save($ticket, TRUE);

    return $this->json(['ticketId' => $ticket->getId()]);
  }

  #[Route('/ticket/{id}', name: 'app_ticket_show', methods: 'GET')]
  public function show($id, TicketRepository $ticketRepository): JsonResponse {
    $ticket = $ticketRepository->find($id);
    try {
      return $this->json([
        'result' => [
          ...$ticket->toArray(),
          // generate barcode image
          // Note: This should be cached for improved performance
          'barcodeImage' => base64_encode($ticket->generateBarcodeImage()),
        ],
      ]);
    } catch (Exception $e) {
      return $this->json(['error' => $e->getMessage()], 500);
    }
  }

}
