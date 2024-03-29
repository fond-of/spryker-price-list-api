<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Model;

use Codeception\Test\Unit;
use Exception;
use FondOfSpryker\Zed\PriceListApi\Business\Mapper\TransferMapper;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToApiFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Plugin\PriceProductsHydrationPluginInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerInterface;
use FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\PriceListApiTransfer;
use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Laminas\Stdlib\ArrayObject;
use Propel\Runtime\Connection\ConnectionInterface;

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
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToApiFacadeInterface
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
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiDataTransfer
     */
    protected $apiDataTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListApiTransfer
     */
    protected $priceListApiTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceListTransfer
     */
    protected $priceListTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceProductTransfer
     */
    protected $priceProductTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiItemTransfer
     */
    protected $apiItemTransferMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    protected $priceProductDimensionTransferMock;

    /**
     * @var array<\FondOfSpryker\Zed\PriceListApi\Dependency\Plugin\PriceProductsHydrationPluginInterface|\PHPUnit\Framework\MockObject\MockObject>
     */
    protected $priceProductsHydrationPluginMocks;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Business\Model\PriceListApi
     */
    protected $priceListApi;

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

        $this->apiQueryContainerMock = $this->getMockBuilder(PriceListApiToApiFacadeInterface::class)
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

        $this->priceProductDimensionTransferMock = $this->getMockBuilder(PriceProductDimensionTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceProductsHydrationPluginMocks = [
            $this->getMockBuilder(PriceProductsHydrationPluginInterface::class)
                ->disableOriginalConstructor()
                ->getMock(),
        ];

        $this->priceListApi = new PriceListApi(
            $this->connectionMock,
            $this->priceListFacadeMock,
            $this->priceProductPriceListFacadeMock,
            $this->transferMapperMock,
            $this->apiQueryContainerMock,
            $this->apiQueryBuilderQueryContainerMock,
            $this->queryContainerMock,
            $this->priceProductsHydrationPluginMocks,
        );
    }

    /**
     * @return void
     */
    public function testAddEntityNotSavedException(): void
    {
        $data = [];

        $this->apiDataTransferMock->expects(static::atLeastOnce())
            ->method('getData')
            ->willReturn($data);

        $this->transferMapperMock->expects(static::atLeastOnce())
            ->method('toTransfer')
            ->with($data)
            ->willReturn($this->priceListApiTransferMock);

        $this->priceListApiTransferMock->expects(static::atLeastOnce())
            ->method('toArray')
            ->willReturn($data);

        $this->connectionMock->expects(static::atLeast(1))
            ->method('beginTransaction')
            ->willReturn(true);

        $this->priceListFacadeMock->expects(static::atLeastOnce())
            ->method('createPriceList')
            ->willThrowException(new Exception());

        $this->connectionMock->expects(static::atLeastOnce())
            ->method('rollback')
            ->willReturn(true);

        $this->connectionMock->expects(static::never())
            ->method('commit');

        $this->priceListApiTransferMock->expects(static::never())
            ->method('getPriceListEntries');

        $this->priceProductsHydrationPluginMocks[0]->expects(static::never())
            ->method('hydrate');

        $this->priceProductTransferMock->expects(static::never())
            ->method('getIdProductAbstract');

        $this->priceProductTransferMock->expects(static::never())
            ->method('getIdProduct');

        $this->priceProductTransferMock->expects(static::never())
            ->method('getPriceDimension');

        $this->priceListTransferMock->expects(static::never())
            ->method('getIdPriceList');

        $this->priceProductDimensionTransferMock->expects(static::never())
            ->method('setIdPriceList');

        $this->priceProductPriceListFacadeMock->expects(static::never())
            ->method('savePriceProductPriceList');

        $this->apiQueryContainerMock->expects(static::never())
            ->method('createApiItem');

        try {
            $this->priceListApi->add($this->apiDataTransferMock);
            static::fail();
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testAdd(): void
    {
        $data = [];
        $idProductAbstract = 1;
        $idPriceList = 1;

        $this->apiDataTransferMock->expects(static::atLeastOnce())
            ->method('getData')
            ->willReturn($data);

        $this->transferMapperMock->expects(static::atLeastOnce())
            ->method('toTransfer')
            ->with($data)
            ->willReturn($this->priceListApiTransferMock);

        $this->priceListApiTransferMock->expects(static::atLeastOnce())
            ->method('toArray')
            ->willReturn($data);

        $this->connectionMock->expects(static::atLeast(2))
            ->method('beginTransaction')
            ->willReturn(true);

        $this->priceListFacadeMock->expects(static::atLeastOnce())
            ->method('createPriceList')
            ->willReturn($this->priceListTransferMock);

        $this->connectionMock->expects(static::atLeast(2))
            ->method('commit')
            ->willReturn(true);

        $this->priceListApiTransferMock->expects(static::atLeastOnce())
            ->method('getPriceListEntries')
            ->willReturn(new ArrayObject([$this->priceProductTransferMock]));

        $this->priceProductsHydrationPluginMocks[0]->expects(static::atLeastOnce())
            ->method('hydrate')
            ->with([$this->priceProductTransferMock])
            ->willReturn([$this->priceProductTransferMock]);

        $this->priceProductTransferMock->expects(static::atLeastOnce())
            ->method('getIdProductAbstract')
            ->willReturn($idProductAbstract);

        $this->priceProductTransferMock->expects(static::never())
            ->method('getIdProduct');

        $this->priceProductTransferMock->expects(static::atLeastOnce())
            ->method('getPriceDimension')
            ->willReturn($this->priceProductDimensionTransferMock);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('getIdPriceList')
            ->willReturn($idPriceList);

        $this->priceProductDimensionTransferMock->expects(static::atLeastOnce())
            ->method('setIdPriceList')
            ->with($idPriceList)
            ->willReturn($this->priceProductDimensionTransferMock);

        $this->priceProductPriceListFacadeMock->expects(static::atLeastOnce())
            ->method('savePriceProductPriceList')
            ->with($this->priceProductTransferMock)
            ->willReturn($this->priceProductTransferMock);

        $this->apiQueryContainerMock->expects(static::atLeastOnce())
            ->method('createApiItem')
            ->with($this->priceListApiTransferMock, 1)
            ->willReturn($this->apiItemTransferMock);

        static::assertEquals($this->apiItemTransferMock, $this->priceListApi->add($this->apiDataTransferMock));
    }

    /**
     * @return void
     */
    public function testUpdatePriceListEntityNotSavedException(): void
    {
        $idPriceList = 1;
        $data = [];

        $this->priceListFacadeMock->expects(static::atLeastOnce())
            ->method('findPriceListById')
            ->with(
                static::callback(
                    static function (PriceListTransfer $priceListTransfer) use ($idPriceList) {
                        return $priceListTransfer->getIdPriceList() === $idPriceList;
                    },
                ),
            )
            ->willReturn($this->priceListTransferMock);

        $this->apiDataTransferMock->expects(static::atLeastOnce())
            ->method('getData')
            ->willReturn($data);

        $this->transferMapperMock->expects(static::atLeastOnce())
            ->method('toTransfer')
            ->willReturn($this->priceListApiTransferMock);

        $this->priceListApiTransferMock->expects(static::atLeastOnce())
            ->method('toArray')
            ->willReturn($data);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('fromArray')
            ->willReturn($this->priceListTransferMock);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('setIdPriceList')
            ->with($idPriceList)
            ->willReturn($this->priceListTransferMock);

        $this->connectionMock->expects(static::atLeast(1))
            ->method('beginTransaction')
            ->willReturn(true);

        $this->priceListFacadeMock->expects(static::atLeastOnce())
            ->method('updatePriceList')
            ->with($this->priceListTransferMock)
            ->willThrowException(new Exception());

        $this->connectionMock->expects(static::never())
            ->method('commit');

        $this->priceListApiTransferMock->expects(static::never())
            ->method('getPriceListEntries');

        $this->priceProductsHydrationPluginMocks[0]->expects(static::never())
            ->method('hydrate');

        $this->priceProductTransferMock->expects(static::never())
            ->method('getIdProductAbstract');

        $this->priceProductTransferMock->expects(static::never())
            ->method('getIdProduct');

        $this->priceProductTransferMock->expects(static::never())
            ->method('getPriceDimension');

        $this->priceListTransferMock->expects(static::never())
            ->method('getIdPriceList');

        $this->priceProductDimensionTransferMock->expects(static::never())
            ->method('setIdPriceList');

        $this->priceProductPriceListFacadeMock->expects(static::never())
            ->method('savePriceProductPriceList');

        $this->apiQueryContainerMock->expects(static::never())
            ->method('createApiItem');

        try {
            $this->priceListApi->update($idPriceList, $this->apiDataTransferMock);
            static::fail();
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testUpdateWithErrorBySavingPriceProductPriceList(): void
    {
        $idPriceList = 1;
        $idProductAbstract = 1;
        $data = [];

        $this->priceListFacadeMock->expects(static::atLeastOnce())
            ->method('findPriceListById')
            ->with(
                static::callback(
                    static function (PriceListTransfer $priceListTransfer) use ($idPriceList) {
                        return $priceListTransfer->getIdPriceList() === $idPriceList;
                    },
                ),
            )
            ->willReturn($this->priceListTransferMock);

        $this->apiDataTransferMock->expects(static::atLeastOnce())
            ->method('getData')
            ->willReturn($data);

        $this->transferMapperMock->expects(static::atLeastOnce())
            ->method('toTransfer')
            ->willReturn($this->priceListApiTransferMock);

        $this->priceListApiTransferMock->expects(static::atLeastOnce())
            ->method('toArray')
            ->willReturn($data);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('fromArray')
            ->willReturn($this->priceListTransferMock);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('setIdPriceList')
            ->with($idPriceList)
            ->willReturn($this->priceListTransferMock);

        $this->connectionMock->expects(static::atLeast(2))
            ->method('beginTransaction')
            ->willReturn(true);

        $this->priceListFacadeMock->expects(static::atLeastOnce())
            ->method('updatePriceList')
            ->with($this->priceListTransferMock)
            ->willReturn($this->priceListTransferMock);

        $this->connectionMock->expects(static::once())
            ->method('commit')
            ->willReturn(true);

        $this->priceListApiTransferMock->expects(static::atLeastOnce())
            ->method('getPriceListEntries')
            ->willReturn(new ArrayObject([$this->priceProductTransferMock]));

        $this->priceProductsHydrationPluginMocks[0]->expects(static::atLeastOnce())
            ->method('hydrate')
            ->with([$this->priceProductTransferMock])
            ->willReturn([$this->priceProductTransferMock]);

        $this->priceProductTransferMock->expects(static::atLeastOnce())
            ->method('getPriceDimension')
            ->willReturn($this->priceProductDimensionTransferMock);

        $this->priceProductTransferMock->expects(static::atLeastOnce())
            ->method('getIdProductAbstract')
            ->willReturn($idProductAbstract);

        $this->priceProductTransferMock->expects(static::never())
            ->method('getIdProduct');

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('getIdPriceList')
            ->willReturn($idPriceList);

        $this->priceProductDimensionTransferMock->expects(static::atLeastOnce())
            ->method('setIdPriceList')
            ->with($idPriceList)
            ->willReturn($this->priceProductDimensionTransferMock);

        $this->priceProductPriceListFacadeMock->expects(static::atLeastOnce())
            ->method('savePriceProductPriceList')
            ->with($this->priceProductTransferMock)
            ->willThrowException(new Exception());

        $this->connectionMock->expects(static::atLeastOnce())
            ->method('rollback')
            ->willReturn(true);

        $this->apiQueryContainerMock->expects(static::never())
            ->method('createApiItem')
            ->with($this->priceListApiTransferMock, $idPriceList)
            ->willReturn($this->apiItemTransferMock);

        try {
            $this->priceListApi->update($idPriceList, $this->apiDataTransferMock);
            static::fail();
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testUpdate(): void
    {
        $idPriceList = 1;
        $idProductAbstract = 1;
        $data = [];

        $this->priceListFacadeMock->expects(static::atLeastOnce())
            ->method('findPriceListById')
            ->with(
                static::callback(
                    static function (PriceListTransfer $priceListTransfer) use ($idPriceList) {
                        return $priceListTransfer->getIdPriceList() === $idPriceList;
                    },
                ),
            )->willReturn($this->priceListTransferMock);

        $this->apiDataTransferMock->expects(static::atLeastOnce())
            ->method('getData')
            ->willReturn($data);

        $this->transferMapperMock->expects(static::atLeastOnce())
            ->method('toTransfer')
            ->willReturn($this->priceListApiTransferMock);

        $this->priceListApiTransferMock->expects(static::atLeastOnce())
            ->method('toArray')
            ->willReturn($data);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('fromArray')
            ->willReturn($this->priceListTransferMock);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('setIdPriceList')
            ->with($idPriceList)
            ->willReturn($this->priceListTransferMock);

        $this->connectionMock->expects(static::atLeast(2))
            ->method('beginTransaction')
            ->willReturn(true);

        $this->priceListFacadeMock->expects(static::atLeastOnce())
            ->method('updatePriceList')
            ->with($this->priceListTransferMock)
            ->willReturn($this->priceListTransferMock);

        $this->connectionMock->expects(static::atLeast(2))
            ->method('commit')
            ->willReturn(true);

        $this->priceListApiTransferMock->expects(static::atLeastOnce())
            ->method('getPriceListEntries')
            ->willReturn(new ArrayObject([$this->priceProductTransferMock]));

        $this->priceProductsHydrationPluginMocks[0]->expects(static::atLeastOnce())
            ->method('hydrate')
            ->with([$this->priceProductTransferMock])
            ->willReturn([$this->priceProductTransferMock]);

        $this->priceProductTransferMock->expects(static::atLeastOnce())
            ->method('getIdProductAbstract')
            ->willReturn($idProductAbstract);

        $this->priceProductTransferMock->expects(static::never())
            ->method('getIdProduct');

        $this->priceProductTransferMock->expects(static::atLeastOnce())
            ->method('getPriceDimension')
            ->willReturn($this->priceProductDimensionTransferMock);

        $this->priceListTransferMock->expects(static::atLeastOnce())
            ->method('getIdPriceList')
            ->willReturn($idPriceList);

        $this->priceProductDimensionTransferMock->expects(static::atLeastOnce())
            ->method('setIdPriceList')
            ->with($idPriceList)
            ->willReturn($this->priceProductDimensionTransferMock);

        $this->priceProductPriceListFacadeMock->expects(static::atLeastOnce())
            ->method('savePriceProductPriceList')
            ->with($this->priceProductTransferMock)
            ->willReturn($this->priceProductTransferMock);

        $this->apiQueryContainerMock->expects(static::atLeastOnce())
            ->method('createApiItem')
            ->with($this->priceListApiTransferMock, 1)
            ->willReturn($this->apiItemTransferMock);

        static::assertEquals(
            $this->apiItemTransferMock,
            $this->priceListApi->update($idPriceList, $this->apiDataTransferMock),
        );
    }

    /**
     * @return void
     */
    public function testGetEntityNotFoundException(): void
    {
        $idPriceList = 1;

        $this->priceListFacadeMock->expects(static::atLeastOnce())
            ->method('findPriceListById')
            ->with(
                static::callback(
                    static function (PriceListTransfer $priceListTransfer) use ($idPriceList) {
                        return $priceListTransfer->getIdPriceList() === $idPriceList;
                    },
                ),
            )->willReturn(null);

        try {
            $this->priceListApi->get($idPriceList);
        } catch (Exception $e) {
        }
    }

    /**
     * @return void
     */
    public function testGet(): void
    {
        $idPriceList = 1;

        $this->priceListFacadeMock->expects(static::atLeastOnce())
            ->method('findPriceListById')
            ->with(
                static::callback(
                    static function (PriceListTransfer $priceListTransfer) use ($idPriceList) {
                        return $priceListTransfer->getIdPriceList() === $idPriceList;
                    },
                ),
            )->willReturn($this->priceListTransferMock);

        $this->apiQueryContainerMock->expects(static::atLeastOnce())
            ->method('createApiItem')
            ->with($this->priceListTransferMock, $idPriceList)
            ->willReturn($this->apiItemTransferMock);

        static::assertEquals($this->apiItemTransferMock, $this->priceListApi->get($idPriceList));
    }
}
