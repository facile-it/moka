<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 4/9/18
 * Time: 8:32 PM
 */

namespace Tests;


use PHPUnit\Framework\TestCase;

class ComplexServiceTest extends TestCase
{
    public function setUp()
    {
        $response = $this->getMockBuilder(MessageInterface::class)
            ->disableOriginalConstructor()->getMock();

        $connection = $this->getMockBuilder(ConnectionInterface::class)
            ->disableOriginalConstructor()->getMock();

        $connection->method('writeStream')->willReturn($response);

        $fakeService = $this->getMockBuilder(ServiceInterface::class)
            ->disableOriginalConstructor()->getMock();

        $fakeService->method('getConnection')->willReturn($connection);

        $responseHandler = $this->getMockBuilder(MessageHandler::class)
            ->disableOriginalConstructor()->getMock();

        $responseHandler->method('__invoke')->willReturn([]);

        $this->service = new ComplexService(
            $fakeService,
            $responseHandler
        );
    }
}