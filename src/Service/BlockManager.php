<?php

declare(strict_types=1);

namespace App\Service;

use App\Controller\AbstractRenderController;
use App\Entity\Block;
use App\Entity\Training;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class BlockManager extends AbstractRenderController
{
    private EntityManagerInterface $entityManager;

    public function __construct(Environment $template, EntityManagerInterface $entityManager) {
        parent::__construct($template);
        $this->entityManager = $entityManager;
    }

    public function getTrainingBlocks(Training $training): string
    {
        $blocksList = $this->entityManager->getRepository(Block::class)->getTrainingBlocks($training->getBlocks());

        $result = "<ol>";

        foreach ($blocksList as $block) {
            $result .= '<li>' . $block['name'] . ' - <b>' . $block['time'] . "'</b></li>";
        }

        $result .= "</ol>";

        return $result;
    }

    public function getTrainingBlocksCsv(Training $training): string
    {
        $blocksList = $this->entityManager->getRepository(Block::class)->getTrainingBlocks($training->getBlocks());

        $result = "";

        foreach ($blocksList as $block) {
            $result .= $block['name'] . ' - ' . $block['time'] . "/";
        }

        return $result;
    }

    public function getTrainingTime(Training $training): string
    {
        $blocksList = $this->entityManager->getRepository(Block::class)->getTrainingTime($training->getBlocks());

        if ($blocksList === null) {
            $blocksList = '0';
        }

        return $blocksList . ' minutos';
    }
}