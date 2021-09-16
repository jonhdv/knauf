<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\BlockManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BlockExtension extends AbstractExtension
{
    private BlockManager $brandManager;

    public function __construct(BlockManager $blockManager)
    {
        $this->blockManager = $blockManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getTime', [$this->blockManager, 'getTrainingTime'])
        ];
    }
}
