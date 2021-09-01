<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

abstract class AbstractRenderController
{
    protected Environment $template;

    public function __construct(Environment $template)
    {
        $this->template = $template;
    }

    protected function render(string $view, array $parameters = [], int $code = Response::HTTP_OK): Response
    {
        $content = $this->template->render($view, $parameters);

        return new Response($content, $code);
    }
}
