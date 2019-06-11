<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Mapper;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\PriceListApiTransfer;

class ApiDataTransferMapper implements ApiDataTransferMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\PriceListApiTransfer
     */
    public function toPriceListApiTransfer(ApiDataTransfer $apiDataTransfer): PriceListApiTransfer
    {
        $priceListTransfer = new PriceListApiTransfer();

        return $priceListTransfer->fromArray($apiDataTransfer->getData());
    }
}
