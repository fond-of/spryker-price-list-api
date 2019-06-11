<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Mapper;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\PriceListApiTransfer;

interface ApiDataTransferMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\PriceListApiTransfer
     */
    public function toPriceListApiTransfer(ApiDataTransfer $apiDataTransfer): PriceListApiTransfer;
}
