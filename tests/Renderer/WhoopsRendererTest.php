<?php

declare(strict_types=1);

namespace Componenta\Error\Renderer\Whoops\Tests\Renderer;

use Componenta\Error\Context\CliContext;
use Componenta\Error\Renderer\WhoopsRenderer;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[TestDox('WhoopsRenderer')]
final class WhoopsRendererTest extends TestCase
{
    public function testRenderReturnsExceptionOutput(): void
    {
        $renderer = new WhoopsRenderer();
        $exception = new RuntimeException('Whoops renderer test');

        $output = $renderer->render($exception, CliContext::fromArgv());

        self::assertStringContainsString('Whoops renderer test', $output);
    }
}
