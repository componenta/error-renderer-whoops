# Componenta Error Renderer Whoops

Интеграция рендерера Whoops для `componenta/error-handler`. Пакет адаптирует `filp/whoops` к `ErrorRendererInterface`.

Используйте пакет, когда страница HTTP-ошибки в разработке должна рендериться через Whoops. Сам обработчик ошибок пакет не регистрирует.

## Установка

```bash
composer require componenta/error-renderer-whoops
```

## Основной API

```php
use Componenta\Error\Renderer\WhoopsRenderer;

$renderer = new WhoopsRenderer();
$html = $renderer->render($exception, $context);
```

`WhoopsRenderer` поддерживает любую переданную пару исключения и контекста. Для HTTP-контекста он выбирает JSON или HTML по заголовкам запроса, для остальных контекстов использует обычный текст.

## Граница пакета

Пакет предоставляет только `WhoopsRenderer`. Контракты отчётов об ошибках и HTTP/CLI-обработчики находятся в `componenta/error-handler`; настройку рендереров приложения и слушателей выполняет `componenta/error-handler-app`.
