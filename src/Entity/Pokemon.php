<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use App\Utils\TimeStamp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PokemonRepository::class)
 * @ORM\Table(indexes={@ORM\Index(name="name_idx", columns={"name"})})
 */
class Pokemon
{
    use TimeStamp;

    /**
     * @Groups({"pokemon_read"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Name cannot be empty or null")
     * @Assert\Type("string")
     * @Groups({"pokemon_read"})
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @Groups({"pokemon_read"})
     * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="pokemons", cascade={"persist"})
     */
    private $types;

    /**
     * @Assert\NotBlank(message="Cannot be empty")
     * @Assert\Type("int")
     * @Groups({"pokemon_read"})
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @Assert\NotBlank(message="Cannot be empty")
     * @Assert\Type("int")
     * @Groups({"pokemon_read"})
     * @ORM\Column(type="integer")
     */
    private $hp;

    /**
     * @Assert\NotBlank(message="Cannot be empty")
     * @Assert\Type("int")
     * @Groups({"pokemon_read"})
     * @ORM\Column(type="integer")
     */
    private $attack;

    /**
     * @Assert\NotBlank(message="Cannot be empty")
     * @Assert\Type("int")
     * @Groups({"pokemon_read"})
     * @ORM\Column(type="integer")
     */
    private $defense;

    /**
     * @Assert\NotBlank(message="Cannot be empty")
     * @Assert\Type("int")
     * @Groups({"pokemon_read"})
     * @ORM\Column(type="integer")
     */
    private $attackSp;

    /**
     * @Assert\NotBlank(message="Cannot be empty")
     * @Assert\Type("int")
     * @Groups({"pokemon_read"})
     * @ORM\Column(type="integer")
     */
    private $defenseSp;

    /**
     * @Assert\NotBlank(message="Cannot be empty")
     * @Assert\Type("int")
     * @Groups({"pokemon_read"})
     * @ORM\Column(type="smallint")
     */
    private $generation;

    /**
     * @Assert\NotBlank(message="Cannot be empty")
     * @Assert\Type("int")
     * @Groups({"pokemon_read"})
     * @ORM\Column(type="integer")
     */
    private $speed;

    /**
     * @Assert\NotBlank(message="Cannot be empty")
     * @Assert\Type("bool")
     * @Groups({"pokemon_read"})
     * @ORM\Column(type="boolean")
     */
    private $legendary;

    public function __construct()
    {
        $this->types = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setTypes($types): self
    {
        $this->types = $types;

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(?Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->contains($type)) {
            $this->types->removeElement($type);
        }

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(?int $hp): self
    {
        $this->hp = $hp;

        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(?int $attack): self
    {
        $this->attack = $attack;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(?int $defense): self
    {
        $this->defense = $defense;

        return $this;
    }

    public function getAttackSp(): ?int
    {
        return $this->attackSp;
    }

    public function setAttackSp(?int $attackSp): self
    {
        $this->attackSp = $attackSp;

        return $this;
    }

    public function getDefenseSp(): ?int
    {
        return $this->defenseSp;
    }

    public function setDefenseSp(?int $defenseSp): self
    {
        $this->defenseSp = $defenseSp;

        return $this;
    }

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(?int $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(?int $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function getLegendary(): ?bool
    {
        return $this->legendary;
    }

    public function setLegendary(?bool $legendary): self
    {
        $this->legendary = $legendary;

        return $this;
    }
}
