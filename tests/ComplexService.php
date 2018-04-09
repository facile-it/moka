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

    /**
     * ComplexService constructor.
     * @param ServiceInterface $service
     * @param MessageHandler $handleResponse
     */
    public function __construct(ServiceInterface $service, MessageHandler $handleResponse)
    {
        $this->service = $service;
        $this->handleResponse = $handleResponse;
    }


    public function __invoke(StreamInterface $stream):array
    {
        $response = $this->service
            ->getConnection()
            ->writeStream($stream);

        return ($this->handleResponse)($response)->getData();
    }
}