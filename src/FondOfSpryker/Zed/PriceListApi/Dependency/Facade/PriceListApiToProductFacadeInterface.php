<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\Facade;

interface PriceListApiToProductFacadeInterface
{
    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku(string $sku): ?int;

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku(string $sku): ?int;
}
