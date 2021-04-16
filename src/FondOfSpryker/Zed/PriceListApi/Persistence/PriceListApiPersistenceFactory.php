<?php

namespace FondOfSpryker\Zed\PriceListApi\Persistence;

use FondOfSpryker\Zed\PriceListApi\PriceListApiDependencyProvider;
use Orm\Zed\PriceList\Persistence\FosPriceListQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \FondOfSpryker\Zed\PriceListApi\PriceListApiConfig getConfig()
 * @method \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface getQueryContainer()
 * @method \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiRepositoryInterface getRepository()
 */
class PriceListApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\PriceList\Persistence\FosPriceListQuery
     */
    public function getPriceListQuery(): FosPriceListQuery
    {
        return $this->getProvidedDependency(PriceListApiDependencyProvider::PROPEL_QUERY_PRICE_LIST);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(PriceListApiDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }
}
