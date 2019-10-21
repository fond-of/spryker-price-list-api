<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Api\Persistence\ApiQueryContainer;

class PriceListApiToApiQueryContainerBridgeTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerBridge
     */
    protected $priceListApiToApiQueryContainerBridge;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Api\Persistence\ApiQueryContainer
     */
    protected $apiQueryContainerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected $abstractTransferMock;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiItemTransfer
     */
    private $apiItemTransferMock;

    /**
     * @var array
     */
    private $transferData;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiCollectionTransfer
     */
    private $apiCollectionTransferMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->apiQueryContainerMock = $this->getMockBuilder(ApiQueryContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->abstractTransferMock = $this->getMockBuilder(AbstractTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiItemTransferMock = $this->getMockBuilder(ApiItemTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiCollectionTransferMock = $this->getMockBuilder(ApiCollectionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->id = 1;

        $this->transferData = [];

        $this->priceListApiToApiQueryContainerBridge = new PriceListApiToApiQueryContainerBridge($this->apiQueryContainerMock);
    }

    /**
     * @return void
     */
    public function testCreateApiItem(): void
    {
        $this->apiQueryContainerMock->expects($this->atLeastOnce())
            ->method('createApiItem')
            ->with($this->abstractTransferMock, $this->id)
            ->willReturn($this->apiItemTransferMock);

        $this->assertInstanceOf(ApiItemTransfer::class, $this->priceListApiToApiQueryContainerBridge->createApiItem($this->abstractTransferMock, $this->id));
    }

    /**
     * @return void
     */
    public function testCreateApiCollection(): void
    {
        $this->apiQueryContainerMock->expects($this->atLeastOnce())
            ->method('createApiCollection')
            ->with($this->transferData)
            ->willReturn($this->apiCollectionTransferMock);

        $this->assertInstanceOf(ApiCollectionTransfer::class, $this->priceListApiToApiQueryContainerBridge->createApiCollection($this->transferData));
    }
}
