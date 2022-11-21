<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController {

  #[Route('/ticket/list', name: 'app_ticket_list', methods: 'GET')]
  public function list(TicketRepository $ticketRepository): JsonResponse {
    $tickets = $ticketRepository->findAll();

    return $this->json([
      'result' => array_map(function ($ticket) {
        return $ticket->formatForOutput();
      }, $tickets),
    ]);
  }

  #[Route('/ticket/create', name: 'app_ticket_create', methods: 'POST')]
  public function create(Request $request, TicketRepository $ticketRepository, EventRepository $eventRepository): JsonResponse {
    $data = json_decode($request->getContent(), TRUE);

    $firstName = $data['firstName'];
    $lastName = $data['lastName'];
    $eventId = $data['eventId'];

    $error = NULL;

    if (empty($firstName)) {
      $error = 'First name is empty';
    }
    elseif (empty($lastName)) {
      $error = 'Last name is empty';
    }
    elseif (empty($eventId)) {
      $error = 'No event selected';
    }

    if ($error) {
      return $this->json(['error' => $error]);
    }

    $ticket = new Ticket();
    $ticket->setFirstName($firstName);
    $ticket->setLastName($lastName);
    $ticket->setEvent($eventRepository->find($eventId));
    $ticketRepository->save($ticket, TRUE);

    return $this->json(['ticketId' => $ticket->getId()]);
  }

  #[Route('/ticket/{id}', name: 'app_ticket_show', methods: 'GET')]
  public function show($id, TicketRepository $ticketRepository): JsonResponse {
    $ticket = $ticketRepository->find($id);

    return $this->json(['result' => $ticket->formatForOutput(),]);
  }

}
