<?php

namespace App\Entity;

class Competitor
{
    use TimestampableTrait;

    private ?int $id = null;
    private string $email;
    private string $name;
    private string $surname;
    private string $position;
    private ?string $foodIntolerances;
    private User $user;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Competitor
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Competitor
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Competitor
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): Competitor
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): Competitor
    {
        $this->position = $position;

        return $this;
    }

    public function getFoodIntolerances(): ?string
    {
        return $this->foodIntolerances;
    }

    public function setFoodIntolerances(?string $foodIntolerances): Competitor
    {
        $this->foodIntolerances = $foodIntolerances;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Competitor
    {
        $this->user = $user;

        return $this;
    }

    
}
