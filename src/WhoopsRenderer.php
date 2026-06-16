<?php

declare(strict_types=1);

namespace Componenta\Error\Renderer;

use Throwable;
use Whoops\Run;
use Whoops\RunInterface;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Componenta\Error\ErrorContextInterface;
use Componenta\Error\Renderer\ErrorRendererInterface;
use Componenta\Error\Context\HttpErrorContextInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Whoops-based error renderer with automatic handler selection
 *
 * Automatically selects appropriate Whoops handler based on context:
 * - HTTP context: JSON or HTML based on request headers
 * - Other contexts: plain text output
 */
readonly class WhoopsRenderer implements ErrorRendererInterface
{
    private RunInterface $run;

    /**
     * Create Whoops renderer
     *
     * @param RunInterface $run Whoops Run instance (optional)
     */
    public function __construct(RunInterface $run = new Run())
    {
        $this->run = $this->configureRunInstance($run);
    }

    /**
     * Configure Whoops Run instance
     */
    private function configureRunInstance(RunInterface $run): RunInterface
    {
        $run->allowQuit(false);
        $run->writeToOutput(false);

        return $run;
    }

    /**
     * Render exception as string
     *
     * @param Throwable $exception Exception to render
     * @param ErrorContextInterface $context Context for handler selection
     * @return string Rendered exception output
     */
    public function render(Throwable $exception, ErrorContextInterface $context): string
    {
        $handler = $context instanceof HttpErrorContextInterface
            ? $this->createHandlerForHttpContext($context)
            : $this->createPlainTextHandler();

        $this->run->clearHandlers();
        $this->run->pushHandler($handler);

        return $this->run->handleException($exception);
    }

    /**
     * Check if renderer supports the exception
     *
     * @param Throwable $exception Exception to check
     * @param ErrorContextInterface $context Context information
     * @return bool Always returns true
     */
    public function supports(Throwable $exception, ErrorContextInterface $context): bool
    {
        return true;
    }

    /**
     * Create appropriate handler for HTTP context
     */
    private function createHandlerForHttpContext(HttpErrorContextInterface $context): HandlerInterface
    {
        return $this->acceptsJson($context->request)
            ? $this->createJsonHandler()
            : $this->createPrettyPageHandler();
    }

    /**
     * Create plain text handler for CLI
     */
    private function createPlainTextHandler(): HandlerInterface
    {
        return new PlainTextHandler();
    }

    /**
     * Create JSON response handler for API requests
     */
    private function createJsonHandler(): HandlerInterface
    {
        return new JsonResponseHandler();
    }

    /**
     * Create pretty page handler for browser requests
     */
    private function createPrettyPageHandler(): HandlerInterface
    {
        return new PrettyPageHandler();
    }

    /**
     * Check if request accepts JSON response
     */
    private function acceptsJson(ServerRequestInterface $request): bool
    {
        if (strtolower($request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest') {
            return true;
        }

        if (str_starts_with($request->getHeaderLine('Accept'), 'application/json')) {
            return true;
        }

        return str_contains($request->getHeaderLine('Content-Type'), 'application/json');
    }
}
