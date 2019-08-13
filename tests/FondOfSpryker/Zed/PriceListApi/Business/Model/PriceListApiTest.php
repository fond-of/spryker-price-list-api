<?php


namespace FondOfSpryker\Zed\PriceListApi\Business\Model;

use Codeception\Test\Unit;
use Exception;
use FondOfSpryker\Zed\PriceListApi\Business\Mapper\TransferMapper;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerInterface;
use FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\PriceListApiTransfer;
use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Api\Business\Exception\EntityNotSavedException;

class PriceListApiTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connectionMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface
     */
    protected $priceListFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface
     */
    protected $priceProductPriceListFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Business\Mapper\TransferMapperInterface
     */
    protected $transferMapperMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerInterface
     */
    protected $apiQueryContainerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerInterface
     */
    protected $apiQueryBuilderQueryContainerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface
     */
    protected $queryContainerMock;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Business\Model\PriceListApi
     */
    protected $priceListApi;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiDataTransfer
     */
    protected $apiDataTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListApiTransfer
     */
    protected $priceListApiTransferMock;

    /**
     * @var array
     */
    protected $transferData;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListTransfer
     */
    protected $priceListTransferMock;

    /**
     * @var string
     */
    private $skuProduct;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceProductTransfer
     */
    private $priceProductTransferMock;

    /**
     * @var int
     */
    private $idPriceList;

    /**
     * @var array
     */
    protected $priceProductHydrationPlugins;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $apiItemTransferMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->connectionMock = $this->getMockBuilder(ConnectionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListFacadeMock = $this->getMockBuilder(PriceListApiToPriceListFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceProductPriceListFacadeMock = $this->getMockBuilder(PriceListApiToPriceProductPriceListFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transferMapperMock = $this->getMockBuilder(TransferMapper::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiQueryContainerMock = $this->getMockBuilder(PriceListApiToApiQueryContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiQueryBuilderQueryContainerMock = $this->getMockBuilder(PriceListApiToApiQueryBuilderQueryContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryContainerMock = $this->getMockBuilder(PriceListApiQueryContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiDataTransferMock = $this->getMockBuilder(ApiDataTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListApiTransferMock = $this->getMockBuilder(PriceListApiTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListTransferMock = $this->getMockBuilder(PriceListTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceProductTransferMock = $this->getMockBuilder(PriceProductTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiItemTransferMock = $this->getMockBuilder(ApiItemTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceProductHydrationPlugins = [];

        $this->idPriceList = 1;

        $this->skuProduct = "Product-name";

        $this->transferData = ["name" => $this->priceProductTransferMock];

        $this->priceListApi = new PriceListApi(
            $this->connectionMock,
            $this->priceListFacadeMock,
            $this->priceProductPriceListFacadeMock,
            $this->transferMapperMock,
            $this->apiQueryContainerMock,
            $this->apiQueryBuilderQueryContainerMock,
            $this->queryContainerMock,
            $this->priceProductHydrationPlugins
        );
    }

    /**
     * @return void
     */
    public function testAddEntityNotSavedException(): void
    {
        $this->apiDataTransferMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn($this->transferData);

        $this->transferMapperMock->expects($this->atLeastOnce())
            ->method('toTransfer')
            ->willReturn($this->priceListApiTransferMock);

        $this->priceListApiTransferMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn([]);

        $this->connectionMock->expects($this->atLeastOnce())
            ->method('beginTransaction')
            ->willReturn(true);

        $this->priceListFacadeMock->expects($this->atLeastOnce())
            ->method('persistPriceList')
            ->withAnyParameters()
            ->willThrowException(new Exception());

        $this->connectionMock->expects($this->atLeastOnce())
            ->method('rollBack')
            ->willReturn(true);

        try {
            $this->priceListApi->add($this->apiDataTransferMock);
            $this->fail();
        } catch (EntityNotSavedException $e) {
        }
    }

    /**
     * @return void
     */
    public function testAdd(): void
    {
        $this->apiDataTransferMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn($this->transferData);

        $this->transferMapperMock->expects($this->atLeastOnce())
            ->method('toTransfer')
            ->willReturn($this->priceListApiTransferMock);

        $this->priceListApiTransferMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn([]);

        $this->connectionMock->expects($this->atLeastOnce())
            ->method('beginTransaction')
            ->willReturn(true);

        $this->priceListFacadeMock->expects($this->atLeastOnce())
            ->method('persistPriceList')
            ->withAnyParameters()
            ->willReturn($this->priceListTransferMock);

        $this->connectionMock->expects($this->atLeastOnce())
            ->method('commit')
            ->willReturn(true);

        $this->priceListApiTransferMock->expects($this->atLeastOnce())
            ->method('getPriceListEntries')
            ->willReturn([]);

        $this->priceListTransferMock->expects($this->atLeastOnce())
            ->method('getIdPriceList')
            ->willReturn($this->idPriceList);

        $this->apiQueryContainerMock->expects($this->atLeastOnce())
            ->method('createApiItem')
            ->willReturn($this->apiItemTransferMock);

        $this->assertInstanceOf(ApiItemTransfer::class, $this->priceListApi->add($this->apiDataTransferMock));
    }

    /**
     * @return void
     */
    public function testUpdateEntityNotFoundException(): void
    {
        try {
            $this->priceListApi->update($this->skuProduct, $this->apiDataTransferMock);
            $this->fail();
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testUpdateEntityNotSavedException(): void
    {
        $this->priceListFacadeMock->expects($this->atLeastOnce())
            ->method('findPriceListByName')
            ->willReturn($this->priceListTransferMock);

        $this->apiDataTransferMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn($this->transferData);

        $this->transferMapperMock->expects($this->atLeastOnce())
            ->method('toTransfer')
            ->willReturn($this->priceListApiTransferMock);

        $this->priceListApiTransferMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn($this->transferData);

        $this->priceListTransferMock->expects($this->atLeastOnce())
            ->method('fromArray')
            ->willReturn(true);

        $this->priceListApiTransferMock->expects($this->atLeastOnce())
            ->method('getPriceListEntries')
            ->willReturn($this->transferData);

        try {
            $this->priceListApi->update($this->skuProduct, $this->apiDataTransferMock);
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $this->priceListFacadeMock->expects($this->atLeastOnce())
            ->method('findPriceListByName')
            ->willReturn($this->priceListTransferMock);

        $this->apiDataTransferMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn($this->transferData);

        $this->transferMapperMock->expects($this->atLeastOnce())
            ->method('toTransfer')
            ->willReturn($this->priceListApiTransferMock);

        $this->priceListApiTransferMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn($this->transferData);

        $this->priceListTransferMock->expects($this->atLeastOnce())
            ->method('fromArray')
            ->willReturn(true);

        $this->priceListApiTransferMock->expects($this->atLeastOnce())
            ->method('getPriceListEntries')
            ->willReturn([]);

        $this->assertInstanceOf(ApiItemTransfer::class, $this->priceListApi->update($this->skuProduct, $this->apiDataTransferMock));
    }

    /**
     * @return void
     */
    public function testGetEntityNotFoundException(): void
    {
        try {
            $this->priceListApi->get($this->idPriceList);
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testGet(): void
    {
        $this->priceListFacadeMock->expects($this->atLeastOnce())
            ->method('findPriceListById')
            ->willReturn($this->priceListTransferMock);

        $this->assertInstanceOf(ApiItemTransfer::class, $this->priceListApi->get($this->idPriceList));
    }
}
