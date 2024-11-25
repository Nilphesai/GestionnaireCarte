<?php

namespace App\Entity;

use App\Repository\DeckCardRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeckCardRepository::class)]
class DeckCard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'deckCards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Deck $deck = null;

    #[ORM\ManyToOne(inversedBy: 'deckCards')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Card $card = null;

    #[ORM\Column]
    private ?int $qtt = null;

    #[ORM\Column(length: 10)]
    private ?string $zone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeck(): ?Deck
    {
        return $this->deck;
    }

    public function setDeck(?Deck $deck): static
    {
        $this->deck = $deck;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): static
    {
        $this->card = $card;

        return $this;
    }

    public function getQtt(): ?int
    {
        return $this->qtt;
    }

    public function setQtt(int $qtt): static
    {
        $this->qtt = $qtt;

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): static
    {
        $this->zone = $zone;

        return $this;
    }
}