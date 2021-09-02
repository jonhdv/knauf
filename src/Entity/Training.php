<?php

namespace App\Entity;


use DateTime;
use Symfony\Component\Validator\Constraints\Collection;

class Training
{
    use TimestampableTrait;

    private ?int $id = null;
    private user $user;
    private Collection $blocks;
    private Collection $users;
    private Datetime $datetime;
    private bool $enabled;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Training
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): user
    {
        return $this->user;
    }

    public function setUser(user $user): Training
    {
        $this->user = $user;

        return $this;
    }

    public function getBlocks(): Collection
    {
        return $this->blocks;
    }

    public function setBlocks(Collection $blocks): Training
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): Training
    {
        $this->users = $users;

        return $this;
    }

    public function getDatetime(): DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(DateTime $datetime): Training
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): Training
    {
        $this->enabled = $enabled;

        return $this;
    }
    
    
}
