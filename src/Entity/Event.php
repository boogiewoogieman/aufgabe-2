<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event {

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = NULL;

  #[ORM\Column(length: 255)]
  private ?string $title = NULL;

  #[ORM\Column(type: Types::DATE_MUTABLE)]
  private ?\DateTimeInterface $date = NULL;

  #[ORM\Column(length: 255)]
  private ?string $city = NULL;

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

  public function getDate(): ?\DateTimeInterface {
    return $this->date;
  }

  public function setDate(\DateTimeInterface $date): self {
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

}
