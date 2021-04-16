<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\Plugin;

interface PriceProductsHydrationPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function hydrate(array $priceProductTransfers): array;
}
