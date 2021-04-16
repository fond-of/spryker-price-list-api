<?php

namespace FondOfSpryker\Zed\PriceListApi\Communication\Plugin\PriceListApi;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\PriceListApi\Business\PriceListApiFacade;
use Generated\Shared\Transfer\PriceProductTransfer;

class ProductIdsPriceProductsHydrationPluginTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Business\PriceListApiFacade
     */
    protected $priceListApiFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject[]|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected $priceProductTransferMocks;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Communication\Plugin\PriceListApi\ProductIdsPriceProductsHydrationPlugin
     */
    protected $productIdsPriceProductsHydrationPlugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->priceListApiFacadeMock = $this->getMockBuilder(PriceListApiFacade::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceProductTransferMocks = [
            $this->getMockBuilder(PriceProductTransfer::class)
                ->disableOriginalConstructor()
                ->getMock(),
        ];

        $this->productIdsPriceProductsHydrationPlugin = new ProductIdsPriceProductsHydrationPlugin();
        $this->productIdsPriceProductsHydrationPlugin->setFacade($this->priceListApiFacadeMock);
    }

    /**
     * @return void
     */
    public function testHydrate(): void
    {
        $this->priceListApiFacadeMock->expects(static::atLeastOnce())
            ->method('hydratePriceProductsWithProductIds')
            ->with($this->priceProductTransferMocks)
            ->willReturn($this->priceProductTransferMocks);

        static::assertEquals(
            $this->priceProductTransferMocks,
            $this->productIdsPriceProductsHydrationPlugin->hydrate($this->priceProductTransferMocks)
        );
    }
}
