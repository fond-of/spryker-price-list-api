<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Hydrator;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiRepositoryInterface;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceProductsHydratorTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface
     */
    protected $productFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiRepositoryInterface
     */
    protected $repositoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject[]|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected $priceProductTransferMocks;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Business\Hydrator\PriceProductsHydrator
     */
    protected $priceProductsHydrator;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->productFacadeMock = $this->getMockBuilder(PriceListApiToProductFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->repositoryMock = $this->getMockBuilder(PriceListApiRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceProductTransferMocks = [
            $this->getMockBuilder(PriceProductTransfer::class)
                ->disableOriginalConstructor()
                ->getMock(),
            $this->getMockBuilder(PriceProductTransfer::class)
                ->disableOriginalConstructor()
                ->getMock(),
        ];

        $this->priceProductsHydrator = new PriceProductsHydrator(
            $this->productFacadeMock,
            $this->repositoryMock
        );
    }

    /**
     * @return void
     */
    public function testHydrateSetIdProduct(): void
    {
        $abstractProductIds = ['Abstract-FOO-1' => 1];
        $concreteProductIds = ['FOO-1' => 1];

        $this->priceProductTransferMocks[0]->expects(static::atLeastOnce())
            ->method('getIdProduct')
            ->willReturn(null);

        $this->priceProductTransferMocks[0]->expects(static::atLeastOnce())
            ->method('getSkuProduct')
            ->willReturn(array_keys($concreteProductIds)[0]);

        $this->priceProductTransferMocks[0]->expects(static::never())
            ->method('getIdProductAbstract');

        $this->priceProductTransferMocks[0]->expects(static::never())
            ->method('getSkuProductAbstract');

        $this->priceProductTransferMocks[1]->expects(static::never())
            ->method('getIdProduct');

        $this->priceProductTransferMocks[1]->expects(static::atLeastOnce())
            ->method('getSkuProduct')
            ->willReturn(null);

        $this->priceProductTransferMocks[1]->expects(static::atLeastOnce())
            ->method('getIdProductAbstract')
            ->willReturn(null);

        $this->priceProductTransferMocks[1]->expects(static::atLeastOnce())
            ->method('getSkuProductAbstract')
            ->willReturn(array_keys($abstractProductIds)[0]);

        $this->repositoryMock->expects(static::atLeastOnce())
            ->method('getProductAbstractIdsByAbstractSkus')
            ->with(array_keys($abstractProductIds))
            ->willReturn($abstractProductIds);

        $this->priceProductTransferMocks[1]->expects(static::atLeastOnce())
            ->method('setIdProductAbstract')
            ->with($abstractProductIds[array_keys($abstractProductIds)[0]])
            ->willReturn($this->priceProductTransferMocks[1]);

        $this->productFacadeMock->expects(static::atLeastOnce())
            ->method('getProductConcreteIdsByConcreteSkus')
            ->with(array_keys($concreteProductIds))
            ->willReturn($concreteProductIds);

        $this->priceProductTransferMocks[0]->expects(static::atLeastOnce())
            ->method('setIdProduct')
            ->with($concreteProductIds[array_keys($concreteProductIds)[0]])
            ->willReturn($this->priceProductTransferMocks[0]);

        static::assertEquals(
            $this->priceProductTransferMocks,
            $this->priceProductsHydrator->hydrate($this->priceProductTransferMocks)
        );
    }
}
