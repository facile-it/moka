<?php
/**
 * Created by PhpStorm.
 * User: angelogiuffredi
 * Date: 4/9/18
 * Time: 8:32 PM
 */

namespace Tests;


use function Moka\Plugin\PHPUnit\moka;
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

    public function setUp2()
    {
        $fakeService = moka(FakeService::class)->stub([
            'getConnection' => moka(ConnectionInterface::class)->stub([
                'writeStream' => moka(MessageInterface::class)
            ])
        ]);

        $responseHandler = moka(MessageHandler::class)->stub([
            '__invoke' => []
        ]);

        $this->service = new ComplexService(
            $fakeService,
            $responseHandler
        );
    }

    public function setUp3()
    {
        $storage = moka(StorageInterface::class, 'storage')->stub([
            'write' => $result = true
        ]);

        $simpleStore = new Store($storage);

        $item = $simpleStore->addItem(new Item);
        $this->assertTrue($item);

        moka('storage')->stub([
            'write' => new NoSpaceLeftOnDeviceException
        ]);

        $this->expectException(NoSpaceLeftOnDeviceException::class);

        $simpleStore->addItem(new Item);
    }

    public function setUp4()
    {
        moka(BarInterface::class)->stub([
            '$property' => 1,
            'isValid' => true,
            'getMock' => moka(AcmeInterface::class),
            'throwException' => new \Exception,
            'returnException' => moka(Exception::class)
        ]);
    }
}