<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Hydrator;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface;
use Generated\Shared\Transfer\PriceProductTransfer;

class ProductIdHydratorTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Business\Hydrator\ProductIdHydrator
     */
    protected $productIdHydrator;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface
     */
    protected $productFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceProductTransfer
     */
    protected $priceProductTransferMock;

    /**
     * @var int
     */
    protected $idProduct;

    /**
     * @var string
     */
    protected $skuProduct;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->productFacadeMock = $this->getMockBuilder(PriceListApiToProductFacadeInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceProductTransferMock = $this->getMockBuilder(PriceProductTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->idProduct = 1;

        $this->skuProduct = "SKU-Product";

        $this->productIdHydrator = new ProductIdHydrator($this->productFacadeMock);
    }

    /**
     * @return void
     */
    public function testHydrateSetIdProduct(): void
    {

        $this->priceProductTransferMock->expects($this->atLeastOnce())
            ->method('getIdProduct')
            ->willReturn(null);

        $this->priceProductTransferMock->expects($this->atLeastOnce())
            ->method('getSkuProduct')
            ->willReturn($this->skuProduct);

        $this->productFacadeMock->expects($this->atLeastOnce())
            ->method('findProductConcreteIdBySku')
            ->willReturn($this->idProduct);

        $this->priceProductTransferMock->expects($this->atLeastOnce())
            ->method('setIdProduct')
            ->with($this->idProduct)
            ->willReturn($this->priceProductTransferMock);

        $this->assertInstanceOf(PriceProductTransfer::class, $this->productIdHydrator->hydrate($this->priceProductTransferMock));
    }

    /**
     * @return void
     */
    public function testHydrateSetItdProductAbstract(): void
    {
        $this->priceProductTransferMock->expects($this->atLeastOnce())
            ->method('getIdProduct')
            ->willReturn($this->idProduct);

        $this->priceProductTransferMock->expects($this->atLeastOnce())
            ->method('getIdProductAbstract')
            ->willReturn(null);

        $this->priceProductTransferMock->expects($this->atLeastOnce())
            ->method('getSkuProductAbstract')
            ->willReturn($this->skuProduct);

        $this->productFacadeMock->expects($this->atLeastOnce())
            ->method('findProductAbstractIdBySku')
            ->willReturn($this->idProduct);

        $this->priceProductTransferMock->expects($this->atLeastOnce())
            ->method('setIdProductAbstract')
            ->with($this->idProduct)
            ->willReturn($this->priceProductTransferMock);

        $this->assertInstanceOf(PriceProductTransfer::class, $this->productIdHydrator->hydrate($this->priceProductTransferMock));
    }
}
