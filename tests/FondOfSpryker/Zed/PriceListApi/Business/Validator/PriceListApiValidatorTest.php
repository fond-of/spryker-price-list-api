<?php


namespace FondOfSpryker\Zed\PriceListApi\Business\Validator;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiDataTransfer;

class PriceListApiValidatorTest extends Unit
{
    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Business\Validator\PriceListApiValidator
     */
    protected $priceListApiValidator;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Generated\Shared\Transfer\ApiDataTransfer
     */
    protected $apiDataTransferMock;

    /**
     * @var array
     */
    protected $transferData;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->apiDataTransferMock = $this->getMockBuilder(ApiDataTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->transferData = [['name' => 'Lorem Ipsum']];

        $this->priceListApiValidator = new PriceListApiValidator();
    }

    /**
     * @return void
     */
    public function testValidate(): void
    {
        $this->apiDataTransferMock->expects($this->atLeastOnce())
            ->method('getData')
            ->willReturn($this->transferData);

        $this->assertIsArray($this->priceListApiValidator->validate($this->apiDataTransferMock));
    }
}
