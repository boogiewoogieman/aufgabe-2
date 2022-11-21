<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorPNG;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event {

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = NULL;

  #[ORM\Column(length: 255)]
  private ?string $title = NULL;

  #[ORM\Column(type: Types::DATE_MUTABLE)]
  private ?DateTimeInterface $date = NULL;

  #[ORM\Column(length: 255)]
  private ?string $city = NULL;

  #[ORM\OneToMany(mappedBy: 'event', targetEntity: Ticket::class)]
  private Collection $tickets;

  public function __construct() {
    $this->tickets = new ArrayCollection();
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getTitle(): ?string {
    return $this->title;
  }

  public function setTitle(string $title): self {
    $this->title = $title;

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
    $this->city = $city;

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

  public function formatForOutput(): array {
    return [
      'id' => $this->getId(),
      'title' => $this->getTitle(),
      'date' => $this->getDate()->format('Y-m-d'),
      'city' => $this->getCity(),
    ];
  }

}
