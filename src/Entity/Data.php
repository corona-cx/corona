<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\DataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DataRepository::class)
 */
class Data
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Area::class, inversedBy="data")
     * @ORM\JoinColumn(nullable=false)
     */
    private $area;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $deathRate = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $cases = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $deaths = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $casesPer100k = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $casesPerPopulation = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $cases7Per100k = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $recovered = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $cases7BlPer100K = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $cases7Bl = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $death7Bl = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $cases7Lk = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $death7Lk = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getDeathRate(): ?float
    {
        return $this->deathRate;
    }

    public function setDeathRate(float $deathRate): self
    {
        $this->deathRate = $deathRate;

        return $this;
    }

    public function getCases(): ?int
    {
        return $this->cases;
    }

    public function setCases(int $cases): self
    {
        $this->cases = $cases;

        return $this;
    }

    public function getDeaths(): ?int
    {
        return $this->deaths;
    }

    public function setDeaths(int $deaths): self
    {
        $this->deaths = $deaths;

        return $this;
    }

    public function getCasesPer100k(): ?float
    {
        return $this->casesPer100k;
    }

    public function setCasesPer100k(float $casesPer100k): self
    {
        $this->casesPer100k = $casesPer100k;

        return $this;
    }

    public function getCasesPerPopulation(): ?float
    {
        return $this->casesPerPopulation;
    }

    public function setCasesPerPopulation(float $casesPerPopulation): self
    {
        $this->casesPerPopulation = $casesPerPopulation;

        return $this;
    }

    public function getCases7Per100k(): ?float
    {
        return $this->cases7Per100k;
    }

    public function setCases7Per100k(float $cases7Per100k): self
    {
        $this->cases7Per100k = $cases7Per100k;

        return $this;
    }

    public function getRecovered(): ?int
    {
        return $this->recovered;
    }

    public function setRecovered(?int $recovered): self
    {
        $this->recovered = $recovered;

        return $this;
    }

    public function getCases7BlPer100K(): ?float
    {
        return $this->cases7BlPer100K;
    }

    public function setCases7BlPer100K(?float $cases7BlPer100K): self
    {
        $this->cases7BlPer100K = $cases7BlPer100K;

        return $this;
    }

    public function getCases7Bl(): ?int
    {
        return $this->cases7Bl;
    }

    public function setCases7Bl(?int $cases7Bl): self
    {
        $this->cases7Bl = $cases7Bl;

        return $this;
    }

    public function getDeath7Bl(): ?int
    {
        return $this->death7Bl;
    }

    public function setDeath7Bl(int $death7Bl): self
    {
        $this->death7Bl = $death7Bl;

        return $this;
    }

    public function getCases7Lk(): ?int
    {
        return $this->cases7Lk;
    }

    public function setCases7Lk(?int $cases7Lk): self
    {
        $this->cases7Lk = $cases7Lk;

        return $this;
    }

    public function getDeath7Lk(): ?int
    {
        return $this->death7Lk;
    }

    public function setDeath7Lk(?int $death7Lk): self
    {
        $this->death7Lk = $death7Lk;

        return $this;
    }
}
