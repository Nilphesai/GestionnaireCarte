<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $attribute = null;

    #[ORM\Column(nullable: true)]
    private ?int $level = null;

    #[ORM\Column(length: 50)]
    private ?string $race = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $effect = null;

    #[ORM\Column(nullable: true)]
    private ?int $att = null;

    #[ORM\Column(nullable: true)]
    private ?int $def = null;

    #[ORM\Column(nullable: true)]
    private ?int $link = null;

    #[ORM\Column(nullable: true)]
    private ?int $scale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkMarker = null;

    #[ORM\Column(length: 255)]
    private ?string $picture = null;

    /**
     * @var Collection<int, Deck>
     */
    #[ORM\ManyToMany(targetEntity: Deck::class, mappedBy: 'card')]
    private Collection $decks;

    public function __construct()
    {
        $this->decks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    public function setAttribute(?string $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(string $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getEffect(): ?string
    {
        return $this->effect;
    }

    public function setEffect(string $effect): static
    {
        $this->effect = $effect;

        return $this;
    }

    public function getAtt(): ?int
    {
        return $this->att;
    }

    public function setAtt(?int $att): static
    {
        $this->att = $att;

        return $this;
    }

    public function getDef(): ?int
    {
        return $this->def;
    }

    public function setDef(?int $def): static
    {
        $this->def = $def;

        return $this;
    }

    public function getLink(): ?int
    {
        return $this->link;
    }

    public function setLink(?int $link): static
    {
        $this->link = $link;

        return $this;
    }

    public function getScale(): ?int
    {
        return $this->scale;
    }

    public function setScale(?int $scale): static
    {
        $this->scale = $scale;

        return $this;
    }

    public function getLinkMarker(): ?string
    {
        return $this->linkMarker;
    }

    public function setLinkMarker(?string $linkMarker): static
    {
        $this->linkMarker = $linkMarker;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, Deck>
     */
    public function getDecks(): Collection
    {
        return $this->decks;
    }

    public function addDeck(Deck $deck): static
    {
        if (!$this->decks->contains($deck)) {
            $this->decks->add($deck);
            $deck->addCard($this);
        }

        return $this;
    }

    public function removeDeck(Deck $deck): static
    {
        if ($this->decks->removeElement($deck)) {
            $deck->removeCard($this);
        }

        return $this;
    }
}
