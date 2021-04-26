<?php

namespace App\Entity;

use App\Repository\ShapeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShapeRepository::class)
 */
class Shape
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $coordList;

    /**
     * @ORM\ManyToOne(targetEntity=Area::class, inversedBy="shapes")
     */
    private $area;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoordList(): ?string
    {
        return $this->coordList;
    }

    public function setCoordList(string $coordList): self
    {
        $this->coordList = $coordList;

        return $this;
    }

    public function getArea(): ?Area
    {
        return $this->area;
    }

    public function setArea(?Area $area): self
    {
        $this->area = $area;

        return $this;
    }
}
