<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductHydrationPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function hydrate(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;
}
