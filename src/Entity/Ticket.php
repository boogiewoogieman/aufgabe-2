<?php

namespace App\Entity;

use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Symfony\Component\Validator\Constraints as Assert;

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
  #[Assert\NotBlank(message: 'First name should not be blank')]
  private ?string $firstName = NULL;

  #[ORM\Column(length: 255)]
  #[Assert\NotBlank(message: 'Last name should not be blank')]
  private ?string $lastName = NULL;

  #[ORM\ManyToOne(inversedBy: 'tickets')]
  #[ORM\JoinColumn(nullable: FALSE)]
  #[Assert\NotBlank(message: 'Event should not be blank')]
  private ?Event $event = NULL;

  static public function fromArray(array $arr): self {
    $ticket = new self();

    if (isset($arr['firstName'])) {
      $ticket->setFirstName($arr['firstName']);
    }
    if (isset($arr['lastName'])) {
      $ticket->setLastName($arr['lastName']);
    }
    if (isset($arr['event'])) {
      $ticket->setEvent($arr['event']);
    }

    return $ticket;
  }

  public function toArray(): array {
    return [
      'id' => $this->getId(),
      'barcodeString' => $this->getBarcode(),
      'firstName' => $this->getFirstName(),
      'lastName' => $this->getLastName(),
      'event' => $this->getEvent()->toArray(),
    ];
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getBarcode(): ?string {
    return $this->barcode;
  }

  /**
   * Generates the barcode image as PNG.
   * Note: This should be cached for improved performance
   *
   * @return string Image data
   * @throws \Picqer\Barcode\Exceptions\BarcodeException
   */
  public function generateBarcodeImage(): string {
    $barcodeGenerator = new BarcodeGeneratorPNG();
    return $barcodeGenerator->getBarcode($this->getBarcode(), BarcodeGenerator::TYPE_CODE_128);
  }

  #[ORM\PrePersist]
  public function preSave(): void {
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
