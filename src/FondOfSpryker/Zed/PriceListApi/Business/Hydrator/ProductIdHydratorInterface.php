<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Hydrator;

use Generated\Shared\Transfer\PriceProductTransfer;

interface ProductIdHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function hydrate(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;
}
