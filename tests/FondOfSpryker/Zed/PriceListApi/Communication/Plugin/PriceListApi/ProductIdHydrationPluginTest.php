<?php

namespace FondOfSpryker\Zed\PriceListApi\Communication\Plugin\PriceListApi;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\PriceListApi\Business\PriceListApiFacade;
use Generated\Shared\Transfer\PriceProductTransfer;

class ProductIdHydrationPluginTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Communication\Plugin\PriceListApi\ProductIdHydrationPlugin
     */
    protected $productIdHydrationPlugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Business\PriceListApiFacade
     */
    protected $priceListApiFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\PriceProductTransfer
     */
    protected $priceProductTransferMock;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $this->priceListApiFacadeMock = $this->getMockBuilder(PriceListApiFacade::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceProductTransferMock = $this->getMockBuilder(PriceProductTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productIdHydrationPlugin = new ProductIdHydrationPlugin();
        $this->productIdHydrationPlugin->setFacade($this->priceListApiFacadeMock);
    }

    /**
     * @return void
     */
    public function testHydrate(): void
    {
        $this->assertInstanceOf(PriceProductTransfer::class, $this->productIdHydrationPlugin->hydrate($this->priceProductTransferMock));
    }
}
