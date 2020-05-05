<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HelperRepository")
 * @ORM\Table(name="helpers")
 */
class Helper
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint", options={"unsigned": true})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="uuid", unique=true)
     */
    private ?UuidInterface $uuid;

    /**
     * @ORM\Column(length=100)
     *
     * @Assert\NotBlank(message="name-first.required")
     * @Assert\Length(max=100)
     */
    public ?string $firstName = '';

    /**
     * @ORM\Column(length=100)
     *
     * @Assert\NotBlank(message="name-last.required")
     * @Assert\Length(max=100)
     */
    public ?string $lastName = '';

    /**
     * @ORM\Column(length=200)
     *
     * @Assert\NotBlank(message="email.required")
     * @Assert\Email()
     * @Assert\Length(max=200)
     */
    public ?string $email = '';

    /**
     * @ORM\Column(length=50)
     *
     * @Assert\NotBlank(message="locality.required")
     */
    public ?string $locality = '';

    /**
     * @ORM\Column(type="boolean")
     */
    public ?bool $isCompany = false;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $masks = 0;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $glasses = 0;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $blouses = 0;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $gel = 0;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $gloves = 0;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $disinfectant = 0;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $paracetamol = 0;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $soap = 0;

    /**
     * @ORM\Column(length=250, nullable=true)
     *
     * @Assert\Length(max=250)
     */
    public ?string $food = null;

    /**
     * @ORM\Column(length=250, nullable=true)
     *
     * @Assert\Length(max=250)
     */
    public ?string $other = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    /**
     * @var Collection|HelpRequest[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\HelpRequest", mappedBy="matchedWith")
     */
    private Collection $requests;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlockedMatch", mappedBy="helper", orphanRemoval=true)
     */
    private Collection $blockedMatches;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->createdAt = new \DateTime();
        $this->requests = new ArrayCollection();
        $this->blockedMatches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getIncompleteName(): string
    {
        return $this->firstName.' '.strtoupper($this->lastName[0]).'.';
    }

    /**
     * @return Collection|HelpRequest[]
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    /**
     * @return Collection|BlockedMatch[]
     */
    public function getBlockedMatches(): Collection
    {
        return $this->blockedMatches;
    }
}
