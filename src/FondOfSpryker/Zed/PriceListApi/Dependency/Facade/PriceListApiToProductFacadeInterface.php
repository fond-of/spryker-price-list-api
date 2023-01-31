<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\Facade;

interface PriceListApiToProductFacadeInterface
{
    /**
     * @param string[] $skus
     *
     * @return int[]
     */
    public function getProductConcreteIdsByConcreteSkus(array $skus): array;
}
