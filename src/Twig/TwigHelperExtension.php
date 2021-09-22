<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\CityType;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigHelperExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getCitiesList', [$this, 'getCitiesList']),
        ];
    }

    public function getCitiesList(): array
    {
        return CityType::$list;
    }
}
