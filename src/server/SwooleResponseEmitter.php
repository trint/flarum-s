<?php
declare(strict_types=1);

namespace Flarum\Server;

use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Response as SwooleResponse;
use Zend\HttpHandlerRunner\Emitter\SapiEmitterTrait;

/**
 * Class SwooleResponseEmitter
 * @package Flarum\Server
 * @author <trint.dev@gmail.com>
 */
class SwooleResponseEmitter
{
    use SapiEmitterTrait;

    public function toSwoole(
        ResponseInterface $psr7Response,
        SwooleResponse $swooleResponse = null
    ) {
        $swooleResponse->status($psr7Response->getStatusCode());
        $this->populateHeaders($psr7Response, $swooleResponse);
        $this->sendResponse($psr7Response, $swooleResponse);
    }

    protected function populateHeaders(
        ResponseInterface $psr7Response,
        SwooleResponse $swooleResponse
    ) {
        $headers = $psr7Response->getHeaders();

        foreach ($headers as $name => $values) {
            $name  = $this->filterHeader($name);

            if ($name === 'Set-Cookie') {
                $swooleResponse->header($name, end($values));
                continue;
            }

            $swooleResponse->header($name, implode(', ', $values));
        }
    }

    protected function sendResponse(
        ResponseInterface $psr7Response,
        SwooleResponse $swooleResponse
    ) {
        $content = $psr7Response->getBody();
        $content->rewind();
        $swooleResponse->end($content->getContents());
    }
}
