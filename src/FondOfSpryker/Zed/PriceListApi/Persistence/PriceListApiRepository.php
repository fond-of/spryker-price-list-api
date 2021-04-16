<?php

namespace FondOfSpryker\Zed\PriceListApi\Persistence;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiPersistenceFactory getFactory()
 */
class PriceListApiRepository extends AbstractRepository implements PriceListApiRepositoryInterface
{
    /**
     * @param string[] $abstractSkus
     *
     * @return int[]
     */
    public function getProductAbstractIdsByAbstractSkus(array $abstractSkus): array
    {
        $results = $this->getFactory()
            ->getProductAbstractQuery()
            ->filterBySku_In($abstractSkus)
            ->select([
                         SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                         SpyProductAbstractTableMap::COL_SKU,
                     ])
            ->find()
            ->getData();

        $formattedResults = [];

        foreach ($results as $result) {
            $key = $result[SpyProductAbstractTableMap::COL_SKU];
            $value = $result[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT];

            $formattedResults[$key] = $value;
        }

        return $formattedResults;
    }
}
