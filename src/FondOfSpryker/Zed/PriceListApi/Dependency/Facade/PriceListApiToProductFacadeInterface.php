<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\Facade;

interface PriceListApiToProductFacadeInterface
{
    /**
     * @param array<string> $skus
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByConcreteSkus(array $skus): array;
}
