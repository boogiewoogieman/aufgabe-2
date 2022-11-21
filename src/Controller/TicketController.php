<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Picqer\Barcode\BarcodeGeneratorPNG;

class TicketController extends AbstractController {

  #[Route('/ticket/list', name: 'app_ticket_list', methods: 'GET')]
  public function list(TicketRepository $ticketRepository): JsonResponse {
    $tickets = $ticketRepository->findAll();

    return $this->json([
      'result' => array_map(function ($ticket) {
        return $this->formatTicket($ticket);
      }, $tickets),
    ]);
  }

  #[Route('/ticket/create', name: 'app_ticket_create', methods: 'POST')]
  public function create(Request $request, TicketRepository $ticketRepository, EventRepository $eventRepository): JsonResponse {
    $data = json_decode($request->getContent(), TRUE);

    $ticket = new Ticket();
    $ticket->setFirstName($data['firstName']);
    $ticket->setLastName($data['lastName']);
    $ticket->setEvent($eventRepository->find($data['eventId']));
    $ticketRepository->save($ticket, TRUE);

    return $this->json([
      'ticketId' => $ticket->getId(),
    ]);
  }

  #[Route('/ticket/{id}', name: 'app_ticket_show', methods: 'GET')]
  public function show($id, TicketRepository $ticketRepository): JsonResponse {
    $ticket = $ticketRepository->find($id);

    return $this->json([
      'result' => $this->formatTicket($ticket),
    ]);
  }

  private function formatTicket(Ticket $ticket): array {
    $generator = new BarcodeGeneratorPNG();

    return [
      'id' => $ticket->getId(),
      'barcodeString' => $ticket->getBarcode(),
      // generate barcode image
      // todo: Cache this for improved performance
      'barcodeImage' => base64_encode($generator->getBarcode($ticket->getBarcode(), $generator::TYPE_CODE_128)),
      'firstName' => $ticket->getFirstName(),
      'lastName' => $ticket->getLastName(),
    ];
  }

}
