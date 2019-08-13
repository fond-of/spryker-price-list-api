<?php

namespace FondOfSpryker\Zed\PriceListApi\Communication\Plugin\Api;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\PriceListApi\Business\PriceListApiFacade;
use FondOfSpryker\Zed\PriceListApi\PriceListApiConfig;
use Generated\Shared\Transfer\ApiDataTransfer;

class PriceListApiValidatorPluginTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Communication\Plugin\Api\PriceListApiValidatorPlugin
     */
    protected $priceListApiValidatorPlugin;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfSpryker\Zed\PriceListApi\Business\PriceListApiFacade
     */
    protected $priceListApiFacadeMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiDataTransfer
     */
    protected $apiDataTransferMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->priceListApiFacadeMock = $this->getMockBuilder(PriceListApiFacade::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->apiDataTransferMock = $this->getMockBuilder(ApiDataTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->priceListApiValidatorPlugin = new PriceListApiValidatorPlugin();

        $this->priceListApiValidatorPlugin->setFacade($this->priceListApiFacadeMock);
    }

    /**
     * @return void
     */
    public function testGetResourceName(): void
    {
        $this->assertSame(PriceListApiConfig::RESOURCE_PRICE_LISTS, $this->priceListApiValidatorPlugin->getResourceName());
    }

    /**
     * @return void
     */
    public function testValidate(): void
    {
        $this->assertIsArray($this->priceListApiValidatorPlugin->validate($this->apiDataTransferMock));
    }
}
