<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VolunteerRepository")
 * @ORM\Table(name="volunteers")
 */
class Volunteer
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
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4();
        $this->createdAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
