<?php

namespace App\DTO;

class AiDTO
{
    public ?string $modelName;
    public ?string $content;
    public function __construct(
        ?string $modelName = null,
        ?string $content = null
    )
    {
        $this->modelName = $modelName;
        $this->content = $content;
    }

    public function getModelName(): ?string {
        return $this->modelName;
    }
    public function getContent(): ?string {
        return $this->content;
    }
}
