<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HelpRequestRepository")
 * @ORM\Table(name="help_requests", indexes={
 *     @ORM\Index(name="help_requests_owner_idx", columns={"owner_uuid"})
 * })
 */
class HelpRequest
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
     * @ORM\Column(type="uuid")
     */
    public ?UuidInterface $ownerUuid;

    /**
     * @ORM\Column(length=100)
     *
     * @Assert\NotBlank(message="name-first.required")
     * @Assert\Length(max=100)
     */
    public ?string $firstName;

    /**
     * @ORM\Column(length=100)
     *
     * @Assert\NotBlank(message="name-last.required")
     * @Assert\Length(max=100)
     */
    public ?string $lastName;

    /**
     * @ORM\Column(length=200)
     *
     * @Assert\NotBlank(message="email.required")
     * @Assert\Email()
     * @Assert\Length(max=200)
     */
    public ?string $email;

    /**
     * @ORM\Column(length=50)
     *
     * @Assert\NotBlank(message="locality.required")
     */
    public ?string $locality = '';

    /**
     * @ORM\Column(length=100, nullable=true)
     */
    public ?string $organization = null;

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
     * @ORM\Column(type="boolean")
     */
    public ?bool $food = false;

    /**
     * @ORM\Column(length=250, nullable=true)
     *
     * @Assert\Length(max=250)
     */
    public ?string $other = null;

    /**
     * @ORM\Column(type="boolean")
     */
    public ?bool $finished = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Helper", inversedBy="requests")
     */
    public ?Helper $matchedWith;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIncompleteName(): string
    {
        return $this->firstName.' '.strtoupper($this->lastName[0]).'.';
    }

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isMatched(): bool
    {
        return null !== $this->matchedWith;
    }
}
