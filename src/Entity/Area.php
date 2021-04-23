<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\AreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=AreaRepository::class)
 * @JMS\ExclusionPolicy("ALL")
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
     * @JMS\Expose()
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    private ?string $type = null;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private ?int $population = null;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     * @JMS\Expose()
     */
    private ?string $nuts = null;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private ?int $populationFederalState = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    private ?string $federalState = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Expose()
     */
    private ?string $county = null;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $shape = null;

    /**
     * @ORM\OneToMany(targetEntity=Data::class, mappedBy="area", orphanRemoval=true)
     */
    private Collection $data;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private ?int $objectId = null;

    /**
     * @ORM\Column(type="integer")
     * @JMS\Expose()
     */
    private ?int $admUnitId = null;

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

    public function setNuts(?string $nuts): self
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

    public function getObjectId(): ?int
    {
        return $this->objectId;
    }

    public function setObjectId(int $objectId): self
    {
        $this->objectId = $objectId;

        return $this;
    }

    public function getAdmUnitId(): ?int
    {
        return $this->admUnitId;
    }

    public function setAdmUnitId(int $admUnitId): self
    {
        $this->admUnitId = $admUnitId;

        return $this;
    }
}
