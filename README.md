# Componenta Error Renderer Whoops

Whoops renderer integration for `componenta/error-handler`. It adapts `filp/whoops` to `ErrorRendererInterface`.

Use this package when a development HTTP error page should be rendered by Whoops. It does not register an error handler by itself.

## Installation

```bash
composer require componenta/error-renderer-whoops
```

## Main API

```php
use Componenta\Error\Renderer\WhoopsRenderer;

$renderer = new WhoopsRenderer();
$html = $renderer->render($exception, $context);
```

`WhoopsRenderer` always supports the passed exception/context pair. For HTTP contexts it chooses JSON or HTML output from request headers; for other contexts it falls back to plain text.

## Boundary

The package only provides `WhoopsRenderer`. Reporting contracts and HTTP/CLI handlers are provided by `componenta/error-handler`; application renderer/listener wiring is provided by `componenta/error-handler-app`.
