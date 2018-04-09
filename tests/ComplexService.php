<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 4/9/18
 * Time: 8:23 PM
 */

namespace Tests;


class ComplexService
{
    /**
     * @var ServiceInterface
     */
    private $service;

    /**
     * @var MessageHandler
     */
    private $handleResponse;

    public function __invoke(StreamInterface $stream):array
    {
        $response = $this->service
            ->getConnection()
            ->writeStream($stream);

        return ($this->handleResponse)($response)->getData();
    }
}