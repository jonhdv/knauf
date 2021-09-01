<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;

trait TimestampableTrait
{
    protected DateTime $updatedAt;
    protected DateTime $createdAt;

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreationDate(): self
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = clone $this->createdAt;

        return $this;
    }

    public function updatedTimeNow(): self
    {
        $this->updatedAt = new DateTime();

        return $this;
    }
}
