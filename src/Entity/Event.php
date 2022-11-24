<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event {

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = NULL;

  #[ORM\Column(length: 255)]
  #[Assert\NotBlank(message: 'Title should not be blank')]
  private ?string $title = NULL;

  #[ORM\Column(type: Types::DATE_MUTABLE)]
  #[Assert\NotBlank(message: 'Date should not be blank')]
  private ?DateTimeInterface $date = NULL;

  #[ORM\Column(length: 255)]
  #[Assert\NotBlank(message: 'City should not be blank')]
  private ?string $city = NULL;

  #[ORM\OneToMany(mappedBy: 'event', targetEntity: Ticket::class)]
  private Collection $tickets;

  public function __construct() {
    $this->tickets = new ArrayCollection();
  }

  /**
   * @throws \Exception
   */
  static public function fromArray(array $arr): self {
    $event = new self();
    if (isset($arr['title'])) {
      $event->setTitle($arr['title']);
    }
    if (isset($arr['date'])) {
      if (is_string($arr['date'])) {
        $event->setDate(new DateTime($arr['date']));
      }
      else {
        $event->setDate($arr['date']);
      }
    }
    if (isset($arr['city'])) {
      $event->setCity($arr['city']);
    }
    return $event;
  }

  public function toArray(): array {
    return [
      'id' => $this->getId(),
      'title' => $this->getTitle(),
      'date' => $this->getDate()->format('Y-m-d'),
      'city' => $this->getCity(),
    ];
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getTitle(): ?string {
    return $this->title;
  }

  public function setTitle(string $title): self {
    $this->title = trim($title);

    return $this;
  }

  public function getDate(): ?DateTimeInterface {
    return $this->date;
  }

  public function setDate(DateTimeInterface $date): self {
    $this->date = $date;

    return $this;
  }

  public function getCity(): ?string {
    return $this->city;
  }

  public function setCity(string $city): self {
    $this->city = trim($city);

    return $this;
  }

  /**
   * @return Collection<int, Ticket>
   */
  public function getTickets(): Collection {
    return $this->tickets;
  }

  public function addTicket(Ticket $ticket): self {
    if (!$this->tickets->contains($ticket)) {
      $this->tickets->add($ticket);
      $ticket->setEvent($this);
    }

    return $this;
  }

  public function removeTicket(Ticket $ticket): self {
    if ($this->tickets->removeElement($ticket)) {
      // set the owning side to null (unless already changed)
      if ($ticket->getEvent() === $this) {
        $ticket->setEvent(NULL);
      }
    }

    return $this;
  }

}
