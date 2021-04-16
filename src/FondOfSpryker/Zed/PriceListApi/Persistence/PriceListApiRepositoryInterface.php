<?php

namespace FondOfSpryker\Zed\PriceListApi\Persistence;

interface PriceListApiRepositoryInterface
{
    /**
     * @param string[] $abstractSkus
     *
     * @return int[]
     */
    public function getProductAbstractIdsByAbstractSkus(array $abstractSkus): array;
}
