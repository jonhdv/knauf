<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;;

class City
{
    use TimestampableTrait;

    private ?int $id = null;
    private string $name;
    private Collection $users;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): City
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): City
    {
        $this->name = $name;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): City
    {
        $this->users = $users;

        return $this;
    }
}
