<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\AreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AreaRepository::class)
 */
class Area
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $type = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $population = null;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private ?string $nuts = null;

    /**
     * @ORM\Column(type="integer")
     */
    private $populationFederalState;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $federalState;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $county;

    /**
     * @ORM\Column(type="text")
     */
    private $shape;

    /**
     * @ORM\OneToMany(targetEntity=Data::class, mappedBy="area", orphanRemoval=true)
     */
    private $data;

    public function __construct()
    {
        $this->data = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->population;
    }

    public function setPopulation(int $population): self
    {
        $this->population = $population;

        return $this;
    }

    public function getNuts(): ?string
    {
        return $this->nuts;
    }

    public function setNuts(string $nuts): self
    {
        $this->nuts = $nuts;

        return $this;
    }

    public function getPopulationFederalState(): ?int
    {
        return $this->populationFederalState;
    }

    public function setPopulationFederalState(int $populationFederalState): self
    {
        $this->populationFederalState = $populationFederalState;

        return $this;
    }

    public function getFederalState(): ?string
    {
        return $this->federalState;
    }

    public function setFederalState(string $federalState): self
    {
        $this->federalState = $federalState;

        return $this;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function setCounty(string $county): self
    {
        $this->county = $county;

        return $this;
    }

    public function getShape(): ?string
    {
        return $this->shape;
    }

    public function setShape(string $shape): self
    {
        $this->shape = $shape;

        return $this;
    }

    /**
     * @return Collection|Data[]
     */
    public function getData(): Collection
    {
        return $this->data;
    }

    public function addData(Data $data): self
    {
        if (!$this->data->contains($data)) {
            $this->data[] = $data;
            $data->setArea($this);
        }

        return $this;
    }

    public function removeData(Data $data): self
    {
        if ($this->data->removeElement($data)) {
            // set the owning side to null (unless already changed)
            if ($data->getArea() === $this) {
                $data->setArea(null);
            }
        }

        return $this;
    }
}
