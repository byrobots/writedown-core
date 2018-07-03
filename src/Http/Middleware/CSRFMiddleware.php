<?php

namespace ByRobots\WriteDown\HTTP\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ByRobots\WriteDown\CSRF\CSRFInterface;

class CSRFMiddleware
{
    /**
     * @var \ByRobots\WriteDown\CSRF\CSRFInterface
     */
    private $csrf;

    /**
     * Get our act together.
     *
     * @param \ByRobots\WriteDown\CSRF\CSRFInterface $csrf
     *
     * @return void
     */
    public function __construct(CSRFInterface $csrf)
    {
        $this->csrf = $csrf;
    }

    /**
     * Validate the CSRF token of the request.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param callable                                 $next
     *
     * @return mixed
     * @throws \Exception
     */
    public function validate(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        switch ($request->getMethod()) {
            case 'POST':
                $token = $request->getParsedBody()['csrf'];
                break;
            case 'GET':
                $token = $request->getQueryParams()['csrf'];
                break;
            default:
                throw new \Exception('Invalid CSRF.');
        }

        if (!$this->csrf->isValid($token)) {
            throw new \Exception('Invalid CSRF.');
        }

        return $next($request, $response);
    }
}
