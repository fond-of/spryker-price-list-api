<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\Facade;

use Codeception\Test\Unit;
use Spryker\Zed\Product\Business\ProductFacade;

class PriceListApiToProductFacadeBridgeTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeBridge
     */
    protected $priceListApiToProductFacadeBridge;

    /**
     * @var string
     */
    protected $skuProduct;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Product\Business\ProductFacade
     */
    protected $productFacadeMock;

    /**
     * @var int
     */
    protected $idProduct;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->productFacadeMock = $this->getMockBuilder(ProductFacade::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->idProduct = 1;

        $this->skuProduct = "SKU-Product";

        $this->priceListApiToProductFacadeBridge = new PriceListApiToProductFacadeBridge($this->productFacadeMock);
    }

    /**
     * @return void
     */
    public function testFindProductAbstractIdBySku(): void
    {
        $this->productFacadeMock->expects($this->atLeastOnce())
            ->method('findProductAbstractIdBySku')
            ->with($this->skuProduct)
            ->willReturn($this->idProduct);

        $this->assertSame($this->idProduct, $this->priceListApiToProductFacadeBridge->findProductAbstractIdBySku($this->skuProduct));
    }

    /**
     * @return void
     */
    public function testFindProductConcreteIdBySku(): void
    {
        $this->productFacadeMock->expects($this->atLeastOnce())
            ->method('findProductConcreteIdBySku')
            ->with($this->skuProduct)
            ->willReturn($this->idProduct);

        $this->assertSame($this->idProduct, $this->priceListApiToProductFacadeBridge->findProductConcreteIdBySku($this->skuProduct));
    }
}
