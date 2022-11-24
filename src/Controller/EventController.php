<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventController extends AbstractController {

  #[Route('/event/list', name: 'app_event_list', methods: 'GET')]
  public function list(EventRepository $eventRepository): JsonResponse {
    $events = $eventRepository->findAll();

    return $this->json([
      'result' => array_map(function ($event) {
        return $event->toArray();
      }, $events),
    ]);
  }

  #[Route('/event/create', name: 'app_event_create', methods: 'POST')]
  public function create(Request $request, EventRepository $eventRepository, ValidatorInterface $validator): JsonResponse {
    $data = json_decode($request->getContent(), TRUE);
    $event = Event::fromArray($data);

    $errors = $validator->validate($event);
    if (count($errors)) {
      return $this->json(['error' => $errors[0]->getMessage()]);
    }

    $eventRepository->save($event, TRUE);

    return $this->json(['eventId' => $event->getId()]);
  }

  #[Route('/event/{id}', name: 'app_event_show', methods: 'GET')]
  public function show($id, EventRepository $eventRepository): JsonResponse {
    $event = $eventRepository->find($id);

    return $this->json(['result' => $event->toArray()]);
  }

}
