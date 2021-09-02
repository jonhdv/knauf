<?php

namespace App\Entity;

class Block
{
    use TimestampableTrait;

    private ?int $id = null;
    private string $name;
    private int $time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Block
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Block
    {
        $this->name = $name;

        return $this;
    }

    public function getTime(): int
    {
        return $this->time;
    }

    public function setTime(int $time): Block
    {
        $this->time = $time;

        return $this;
    }
}
