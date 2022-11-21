<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController {

  #[Route('/event/list', name: 'app_event_list', methods: 'GET')]
  public function list(EventRepository $eventRepository): JsonResponse {
    $events = $eventRepository->findAll();

    return $this->json([
      'result' => array_map(function ($event) {
        return [
          'id' => $event->getId(),
          'title' => $event->getTitle(),
          'date' => $event->getDate(),
          'city' => $event->getCity(),
        ];
      }, $events),
    ]);
  }

  #[Route('/event/create', name: 'app_event_create', methods: 'POST')]
  public function create(Request $request, EventRepository $eventRepository): JsonResponse {
    $data = json_decode($request->getContent(), TRUE);

    $event = new Event();
    $event->setTitle($data['title']);
    $event->setDate(new \DateTime($data['date']));
    $event->setCity($data['city']);
    $eventRepository->save($event, TRUE);

    return $this->json([
      'eventId' => $event->getId(),
    ]);
  }

  #[Route('/event/{id}', name: 'app_event_show', methods: 'GET')]
  public function show($id, EventRepository $eventRepository): JsonResponse {
    $event = $eventRepository->find($id);

    return $this->json([
      'result' => [
        'id' => $event->getId(),
        'title' => $event->getTitle(),
        'date' => $event->getDate(),
        'city' => $event->getCity(),
      ],
    ]);
  }

}
