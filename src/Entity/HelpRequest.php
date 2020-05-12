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
    public const TYPE_MASKS = 'masks';
    public const TYPE_GLASSES = 'glasses';
    public const TYPE_BLOUSES = 'blouses';
    public const TYPE_GEL = 'gel';
    public const TYPE_GLOVES = 'gloves';
    public const TYPE_SOAP = 'soap';
    public const TYPE_FOOD = 'food';
    public const TYPE_OTHER = 'other';

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
     * @ORM\Column(length=50, nullable=true)
     *
     * @Assert\Length(max=50)
     */
    public ?string $phone = null;

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
     * @ORM\Column(length=50)
     */
    public ?string $type;

    /**
     * @ORM\Column(type="integer")
     */
    public ?int $quantity = 0;

    /**
     * @ORM\Column(length=250, nullable=true)
     */
    public ?string $details;

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

    public static function getTypes()
    {
        return [
            self::TYPE_MASKS,
            self::TYPE_GLASSES,
            self::TYPE_BLOUSES,
            self::TYPE_GEL,
            self::TYPE_GLOVES,
            self::TYPE_SOAP,
            self::TYPE_FOOD,
            self::TYPE_OTHER,
        ];
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
