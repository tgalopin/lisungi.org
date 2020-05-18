<?php

namespace App\Model;

class CompositeHelpRequestDetail
{
    private string $type;

    public ?bool $need = false;
    public ?int $quantity = 0;
    public ?string $details = null;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
