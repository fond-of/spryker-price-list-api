<?php

namespace FondOfSpryker\Zed\PriceListApi\Business;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\PriceListApi\Business\Hydrator\ProductIdHydratorInterface;
use FondOfSpryker\Zed\PriceListApi\Business\Model\PriceListApiInterface;
use FondOfSpryker\Zed\PriceListApi\Business\Validator\PriceListApiValidatorInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerBridge;
use FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainer;
use FondOfSpryker\Zed\PriceListApi\PriceListApiDependencyProvider;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Kernel\Container;

class PriceListApiBusinessFactoryTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Business\PriceListApiBusinessFactory
     */
    protected $priceListApiBusinessFactory;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Container
     */
    protected $containerMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface
     */
    protected $priceListApiToProductFacadeInterfaceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    private $connectionInterfaceMock;

    /**
     * @var array
     */
    private $priceProductHydrationPlugins;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface
     */
    private $facadePriceListMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface
     */
    private $facadePriceProductPriceListMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerInterface
     */
    private $queryContainerApiMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerInterface
     */
    private $queryContainerApiQueryBuilderMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainer
     */
    protected $queryContainerMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListApiToProductFacadeInterfaceMock = $this->getMockBuilder(PriceListApiToProductFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->connectionInterfaceMock = $this->getMockBuilder(ConnectionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->facadePriceListMock = $this->getMockBuilder(PriceListApiToPriceListFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->facadePriceProductPriceListMock = $this->getMockBuilder(PriceListApiToPriceProductPriceListFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryContainerApiMock = $this->getMockBuilder(PriceListApiToApiQueryContainerBridge::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryContainerMock = $this->getMockBuilder(PriceListApiQueryContainer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->queryContainerApiQueryBuilderMock = $this->getMockBuilder(PriceListApiToApiQueryBuilderQueryContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceProductHydrationPlugins = [];

        $this->priceListApiBusinessFactory = new PriceListApiBusinessFactory();

        $this->priceListApiBusinessFactory->setContainer($this->containerMock);
        $this->priceListApiBusinessFactory->setQueryContainer($this->queryContainerMock);
    }

    /**
     * @return void
     */
    public function testGetProductIdHydrator(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->with(PriceListApiDependencyProvider::FACADE_PRODUCT)
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->with(PriceListApiDependencyProvider::FACADE_PRODUCT)
            ->willReturn($this->priceListApiToProductFacadeInterfaceMock);

        $this->assertInstanceOf(ProductIdHydratorInterface::class, $this->priceListApiBusinessFactory->createProductIdHydrator());
    }

    /**
     * @return void
     */
    public function testPriceListApiValidator(): void
    {
        $this->assertInstanceOf(PriceListApiValidatorInterface::class, $this->priceListApiBusinessFactory->createPriceListApiValidator());
    }

    /**
     * @return void
     */
    public function testCreateProductListApi(): void
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('has')
            ->withConsecutive(
                [PriceListApiDependencyProvider::PROPEL_CONNECTION],
                [PriceListApiDependencyProvider::FACADE_PRICE_LIST],
                [PriceListApiDependencyProvider::FACADE_PRICE_PRODUCT_PRICE_LIST],
                [PriceListApiDependencyProvider::QUERY_CONTAINER_API],
                [PriceListApiDependencyProvider::QUERY_CONTAINER_API_QUERY_BUILDER],
                [PriceListApiDependencyProvider::PLUGINS_PRICE_PRODUCT_HYDRATION]
            )->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('get')
            ->withConsecutive(
                [PriceListApiDependencyProvider::PROPEL_CONNECTION],
                [PriceListApiDependencyProvider::FACADE_PRICE_LIST],
                [PriceListApiDependencyProvider::FACADE_PRICE_PRODUCT_PRICE_LIST],
                [PriceListApiDependencyProvider::QUERY_CONTAINER_API],
                [PriceListApiDependencyProvider::QUERY_CONTAINER_API_QUERY_BUILDER],
                [PriceListApiDependencyProvider::PLUGINS_PRICE_PRODUCT_HYDRATION]
            )
            ->willReturnOnConsecutiveCalls(
                $this->connectionInterfaceMock,
                $this->facadePriceListMock,
                $this->facadePriceProductPriceListMock,
                $this->queryContainerApiMock,
                $this->queryContainerApiQueryBuilderMock,
                $this->priceProductHydrationPlugins
            );

        $this->assertInstanceOf(PriceListApiInterface::class, $this->priceListApiBusinessFactory->createProductListApi());
    }
}
