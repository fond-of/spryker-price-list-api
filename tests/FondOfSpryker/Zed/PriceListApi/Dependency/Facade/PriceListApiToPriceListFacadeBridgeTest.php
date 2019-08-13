<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\Facade;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\PriceList\Business\PriceListFacadeInterface;
use Generated\Shared\Transfer\PriceListTransfer;

class PriceListApiToPriceListFacadeBridgeTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceList\Business\PriceListFacadeInterface
     */
    protected $priceListFacadeInterfaceMock;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeBridge
     */
    protected $priceListApiToPriceListFacadeBridge;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListTransfer
     */
    protected $priceListTransferMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->priceListFacadeInterfaceMock = $this->getMockBuilder(PriceListFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListTransferMock = $this->getMockBuilder(PriceListTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListApiToPriceListFacadeBridge = new PriceListApiToPriceListFacadeBridge($this->priceListFacadeInterfaceMock);
    }

    /**
     * @return void
     */
    public function testFindPriceListByName(): void
    {
        $this->priceListFacadeInterfaceMock->expects($this->atLeastOnce())
            ->method('findPriceListByName')
            ->with($this->priceListTransferMock)
            ->willReturn($this->priceListTransferMock);

        $this->assertInstanceOf(PriceListTransfer::class, $this->priceListApiToPriceListFacadeBridge->findPriceListByName($this->priceListTransferMock));
    }

    /**
     * @return void
     */
    public function testPersistPriceList(): void
    {
        $this->priceListFacadeInterfaceMock->expects($this->atLeastOnce())
            ->method('persistPriceList')
            ->with($this->priceListTransferMock)
            ->willReturn($this->priceListTransferMock);

        $this->assertInstanceOf(PriceListTransfer::class, $this->priceListApiToPriceListFacadeBridge->persistPriceList($this->priceListTransferMock));
    }

    /**
     * @return void
     */
    public function testFindPriceListById(): void
    {
        $this->priceListFacadeInterfaceMock->expects($this->atLeastOnce())
            ->method('findPriceListById')
            ->with($this->priceListTransferMock)
            ->willReturn($this->priceListTransferMock);

        $this->assertInstanceOf(PriceListTransfer::class, $this->priceListApiToPriceListFacadeBridge->findPriceListById($this->priceListTransferMock));
    }
}
