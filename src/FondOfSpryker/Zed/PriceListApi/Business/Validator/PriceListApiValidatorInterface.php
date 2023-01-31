<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Validator;

use Generated\Shared\Transfer\ApiDataTransfer;

interface PriceListApiValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return array<string>
     */
    public function validate(ApiDataTransfer $apiDataTransfer): array;
}
