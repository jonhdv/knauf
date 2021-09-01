<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class IndexController extends AbstractRenderController
{

    public function __construct(Environment $template)
    {
        parent::__construct($template);
    }

    public function __invoke(): Response
    {
        return $this->render('index.html.twig');
    }
}