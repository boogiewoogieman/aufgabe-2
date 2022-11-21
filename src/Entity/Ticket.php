<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Ticket {

  const BARCODE_MAX_LENGTH = 8;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = NULL;

  #[ORM\GeneratedValue]
  #[ORM\Column(length: self::BARCODE_MAX_LENGTH, unique: TRUE)]
  /**
   * The barcode value is an alphanumeric string with a maximum length of
   * Ticket::BARCODE_MAX_LENGTH. It is generated when the entity is saved
   * (see Ticket->preSave).
   */
  private ?string $barcode = NULL;

  #[ORM\Column(length: 255)]
  private ?string $firstName = NULL;

  #[ORM\Column(length: 255)]
  private ?string $lastName = NULL;

  #[ORM\ManyToOne(inversedBy: 'tickets')]
  #[ORM\JoinColumn(nullable: FALSE)]
  private ?Event $event = NULL;

  public function getId(): ?int {
    return $this->id;
  }

  public function getBarcode(): ?string {
    return $this->barcode;
  }

  #[ORM\PrePersist]
  public function preSave() {
    // Generate barcode value
    // We divide max length by 2 because we will save the barcode as a hex string
    // Duplications cannot be ruled out. Therefore a better generation method
    // should be implemented in the future. For now, at least the column is marked as unique.
    $data = random_bytes(self::BARCODE_MAX_LENGTH / 2);
    $this->barcode = bin2hex($data);
  }

  public function getFirstName(): ?string {
    return $this->firstName;
  }

  public function setFirstName(string $firstName): self {
    $this->firstName = $firstName;

    return $this;
  }

  public function getLastName(): ?string {
    return $this->lastName;
  }

  public function setLastName(string $lastName): self {
    $this->lastName = $lastName;

    return $this;
  }

  public function getEvent(): ?Event {
    return $this->event;
  }

  public function setEvent(?Event $event): self {
    $this->event = $event;

    return $this;
  }

}
