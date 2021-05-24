<?php
declare(strict_types=1);

namespace Gg2\Ebit\Test\Unit\Block\Html;

use Gg2\Ebit\Block\Html\Ebit;
use Gg2\Ebit\Helper\Data;
use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class EbitTest extends TestCase
{
    /**
     * @var MockObject|Ebit
     */
    private $object;
    /**
     * @var MockObject|Session
     */
    private $session;

    public function testTrue(): void
    {
        $this->assertTrue(true);
    }

    public function testEbitInstance(): void
    {
        $this->assertInstanceOf(Ebit::class, $this->object);
    }

    public function testImplementsBlockInterface(): void
    {
        $this->assertInstanceOf(BlockInterface::class, $this->object);
    }

    public function testGetEbitUriParamsIsNull(): void
    {
        $this->assertNull($this->object->getEbitUriParams());
    }

    public function testGetEbitUriParamsIsString(): void
    {
        $order = $this->createMock(Order::class);
        $this->session
            ->expects($this->once())
            ->method('getLastRealOrder')
            ->willReturn($order);

        $this->assertIsString($this->object->getEbitUriParams());
    }

    protected function setUp(): void
    {
        $contextMock = $this->createMock(Context::class);
        $helperMock = $this->createMock(Data::class);
        $this->session = $this->createMock(Session::class);
        $this->object = new Ebit($contextMock, $this->session, $helperMock);
    }

    protected function tearDown(): void
    {
        $this->object = null;
        $this->session = null;
    }
}
