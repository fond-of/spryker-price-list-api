<?php

namespace FondOfSpryker\Zed\PriceListApi\Persistence;

use FondOfSpryker\Zed\CompanyApi\CompanyApiDependencyProvider;
use FondOfSpryker\Zed\PriceListApi\PriceListApiDependencyProvider;
use Orm\Zed\PriceList\Persistence\FosPriceListQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \FondOfSpryker\Zed\PriceListApi\PriceListApiConfig getConfig()
 * @method \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface getQueryContainer()
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
}
