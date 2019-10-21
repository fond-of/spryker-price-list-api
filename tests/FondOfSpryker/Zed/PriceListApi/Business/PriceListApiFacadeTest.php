<?php

namespace FondOfSpryker\Zed\PriceListApi\Business;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\PriceListApi\Business\Hydrator\ProductIdHydratorInterface;
use FondOfSpryker\Zed\PriceListApi\Business\Model\PriceListApi;
use FondOfSpryker\Zed\PriceListApi\Business\Validator\PriceListApiValidatorInterface;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceListApiFacadeTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Business\PriceListApiFacade
     */
    protected $priceListApiFacade;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiDataTransfer
     */
    protected $apiDataTransferMock;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Business\PriceListApiBusinessFactory
     */
    protected $priceListApiBusinessFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Business\Model\PriceListApi
     */
    protected $priceListApiMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiItemTransfer
     */
    protected $apiItemTransferMock;

    /**
     * @var int
     */
    protected $idPriceList;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceProductTransfer
     */
    protected $priceProductTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Business\Hydrator\ProductIdHydratorInterface
     */
    protected $productIdHydratorInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiRequestTransfer
     */
    protected $apiRequestTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiCollectionTransfer
     */
    protected $apiCollectionTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Business\Validator\PriceListApiValidatorInterface
     */
    protected $priceListApiValidatorInterfaceMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->apiDataTransferMock = $this->getMockBuilder(ApiDataTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListApiBusinessFactoryMock = $this->getMockBuilder(PriceListApiBusinessFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListApiMock = $this->getMockBuilder(PriceListApi::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiItemTransferMock = $this->getMockBuilder(ApiItemTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceProductTransferMock = $this->getMockBuilder(PriceProductTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productIdHydratorInterfaceMock = $this->getMockBuilder(ProductIdHydratorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiRequestTransferMock = $this->getMockBuilder(ApiRequestTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiCollectionTransferMock = $this->getMockBuilder(ApiCollectionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListApiValidatorInterfaceMock = $this->getMockBuilder(PriceListApiValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->idPriceList = 1;

        $this->priceListApiFacade = new PriceListApiFacade();
        $this->priceListApiFacade->setFactory($this->priceListApiBusinessFactoryMock);
    }

    /**
     * @return void
     */
    public function testAddPriceList(): void
    {
        $this->priceListApiBusinessFactoryMock->expects($this->atLeastOnce())
            ->method('createProductListApi')
            ->willReturn($this->priceListApiMock);

        $this->priceListApiMock->expects($this->atLeastOnce())
            ->method('add')
            ->with($this->apiDataTransferMock)
            ->willReturn($this->apiItemTransferMock);

        $this->assertInstanceOf(ApiItemTransfer::class, $this->priceListApiFacade->addPriceList($this->apiDataTransferMock));
    }

    /**
     * @return void
     */
    public function testUpdatePriceList(): void
    {
        $this->priceListApiBusinessFactoryMock->expects($this->atLeastOnce())
            ->method('createProductListApi')
            ->willReturn($this->priceListApiMock);

        $this->priceListApiMock->expects($this->atLeastOnce())
            ->method('update')
            ->with($this->idPriceList, $this->apiDataTransferMock)
            ->willReturn($this->apiItemTransferMock);

        $this->assertInstanceOf(ApiItemTransfer::class, $this->priceListApiFacade->updatePriceList($this->idPriceList, $this->apiDataTransferMock));
    }

    /**
     * @return void
     */
    public function testHydrateProductId(): void
    {
        $this->priceListApiBusinessFactoryMock->expects($this->atLeastOnce())
            ->method('createProductIdHydrator')
            ->willReturn($this->productIdHydratorInterfaceMock);

        $this->productIdHydratorInterfaceMock->expects($this->atLeastOnce())
            ->method('hydrate')
            ->with($this->priceProductTransferMock)
            ->willReturn($this->priceProductTransferMock);

        $this->assertInstanceOf(PriceProductTransfer::class, $this->priceListApiFacade->hydrateProductId($this->priceProductTransferMock));
    }

    /**
     * @return void
     */
    public function testGetPriceList(): void
    {
        $this->priceListApiBusinessFactoryMock->expects($this->atLeastOnce())
            ->method('createProductListApi')
            ->willReturn($this->priceListApiMock);

        $this->priceListApiMock->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->idPriceList)
            ->willReturn($this->apiItemTransferMock);

        $this->assertInstanceOf(ApiItemTransfer::class, $this->priceListApiFacade->getPriceList($this->idPriceList));
    }

    /**
     * @return void
     */
    public function testFindPriceLists(): void
    {
        $this->priceListApiBusinessFactoryMock->expects($this->atLeastOnce())
            ->method('createProductListApi')
            ->willReturn($this->priceListApiMock);

        $this->priceListApiMock->expects($this->atLeastOnce())
            ->method('find')
            ->with($this->apiRequestTransferMock)
            ->willReturn($this->apiCollectionTransferMock);

        $this->assertInstanceOf(ApiCollectionTransfer::class, $this->priceListApiFacade->findPriceLists($this->apiRequestTransferMock));
    }

    /**
     * @return void
     */
    public function testValidate(): void
    {

        $this->priceListApiBusinessFactoryMock->expects($this->atLeastOnce())
            ->method('createPriceListApiValidator')
            ->willReturn($this->priceListApiValidatorInterfaceMock);

        $this->priceListApiValidatorInterfaceMock->expects($this->atLeastOnce())
            ->method('validate')
            ->willReturn([]);

        $this->assertIsArray($this->priceListApiFacade->validate($this->apiDataTransferMock));
    }
}
