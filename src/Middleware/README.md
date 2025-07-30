## Server Middlewares

#### [LazyLoadingMiddleware](/src/Server/Middleware/LazyLoadingMiddleware.php)
A special type of middleware that all ensures that all middlewares piped to the middleware pipeline are done so in a lazy fashion, i.e encapsulates other middlewares so that they are not instantiated until required.

Handles resolving `string`, `array`, `callable`, `\Psr\Http\Server\RequestHandlerInterface` instances into `\Psr\Http\Server\MiddlewareInterface` instances for processing.
