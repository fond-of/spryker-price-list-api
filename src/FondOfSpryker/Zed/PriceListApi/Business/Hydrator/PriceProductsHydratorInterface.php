<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Hydrator;

interface PriceProductsHydratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function hydrate(array $priceProductTransfers): array;
}
