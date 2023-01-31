<?php

namespace FondOfSpryker\Zed\PriceListApi\Persistence;

interface PriceListApiRepositoryInterface
{
    /**
     * @param array<string> $abstractSkus
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByAbstractSkus(array $abstractSkus): array;
}
