<?php

namespace App\Entity;


use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Training
{
    use TimestampableTrait;

    private ?int $id = null;
    private user $user;
    private bool $studioConfirmed;
    private ?array $blocks = [];
    private ?Collection $competitors;
    private ?Datetime $datetime;
    private bool $enabled;
    private bool $sent;

    public function __construct(user $user)
    {
        $this->user = $user;
        $this->studioConfirmed = false;
        $this->enabled = false;
        $this->sent = false;
        $this->competitors = new ArrayCollection();
        $this->datetime = null;
    }


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

    public function getStudioConfirmed(): bool
    {
        return $this->studioConfirmed;
    }

    public function setStudioConfirmed(bool $studioConfirmed): Training
    {
        $this->studioConfirmed = $studioConfirmed;

        return $this;
    }

    public function getBlocks(): ?array
    {
        return $this->blocks;
    }

    public function setBlocks(?array $blocks): Training
    {
        $this->blocks = $blocks;

        return $this;
    }

    public function getcompetitors(): ?Collection
    {
        return $this->competitors;
    }

    public function setcompetitors(?Collection $competitors): Training
    {
        $this->competitors = $competitors;

        return $this;
    }

    public function addCompetitor (Competitor $competitor) {
        $this->competitors[] = $competitor;
    }

    public function getDatetime(): ?DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(?DateTime $datetime): Training
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

    public function isSent(): bool
    {
        return $this->sent;
    }

    public function setSent(bool $sent): Training
    {
        $this->sent = $sent;

        return $this;
    }
}
