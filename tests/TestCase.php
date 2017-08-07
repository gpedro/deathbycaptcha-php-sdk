<?php

namespace juniorb2ss\DeathByCaptcha\Tests;

use juniorb2ss\DeathByCaptcha\DeathByCaptcha;
use PHPUnit_Framework_TestCase as BaseTestCase;
use juniorb2ss\DeathByCaptcha\HttpClientService;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use Mockery;

abstract class TestCase extends BaseTestCase
{
    public function mock(string $contentToBody = '{}', callable $handlerResponse = null)
    {
        $httpClientMock = $this->clientMock($contentToBody, $handlerResponse);

        $service = (new DeathByCaptcha('username', 'password'))
                       ->setHttpClient($httpClientMock);

        return $service;
    }

    protected function middlewareResponse(callable $handler = null)
    {
        return (Middleware::mapResponse(function (ResponseInterface $response) use ($handler) {
            $response = $response->withStatus(200);
            $response = ($handler !== null) ? $handler($response) : $response;

            return $response;
        }));
    }

    protected function clientMock(string $contentToBody, callable $handlerResponse = null)
    {
        $body = $this->getStubContent($contentToBody);

        $response = new MockHandler([
            new GuzzleResponse(200, ['Content-Type' => 'application/json'], $body)
        ]);

        $handlerStack = HandlerStack::create($response);
        $middleware = $this->middlewareResponse($handlerResponse);
        $handlerStack->push($middleware);

        $guzzle = new GuzzleClient([
            'handler' => $handlerStack
        ]);

        return $guzzle;
    }

    /**
     *
     * @param  string $content content or filename
     * @return string content
     */
    protected function getStubContent($content)
    {
        $base_path = dirname(__FILE__) . '/stubs/';

        if (is_file($base_path . $content)) {
            return file_get_contents($base_path . $content);
        }

        return $content;
    }
}