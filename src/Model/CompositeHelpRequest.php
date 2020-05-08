<?php

namespace App\Model;

use App\Entity\HelpRequest;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CompositeHelpRequest
{
    /**
     * @Assert\NotBlank(message="name-first.required")
     * @Assert\Length(max=100)
     */
    public ?string $firstName = '';

    /**
     * @Assert\NotBlank(message="name-last.required")
     * @Assert\Length(max=100)
     */
    public ?string $lastName = '';

    /**
     * @Assert\NotBlank(message="email.required")
     * @Assert\Email()
     * @Assert\Length(max=200)
     */
    public ?string $email = '';

    /**
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
     * @var array|CompositeHelpRequestDetail[]
     *
     * @Assert\Valid()
     */
    public array $details;

    public function __construct()
    {
        $this->details = [];
        foreach (HelpRequest::getTypes() as $type) {
            $this->details[$type] = new CompositeHelpRequestDetail($type);
        }
    }

    /**
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (!$this->details) {
            $context->addViolation('needs.at-least-one');
        }
    }

    public function createStandaloneRequests(UuidInterface $ownerUuid)
    {
        $requests = [];
        foreach ($this->details as $detail) {
            if (!$detail->need) {
                continue;
            }

            $request = new HelpRequest();
            $request->ownerUuid = $ownerUuid;
            $request->firstName = $this->firstName;
            $request->lastName = $this->lastName;
            $request->email = strtolower($this->email);
            $request->organization = $this->organization;
            $request->locality = $this->locality;
            $request->type = $detail->getType();
            $request->quantity = $detail->quantity;
            $request->details = $detail->details;

            $requests[] = $request;
        }

        return $requests;
    }
}
