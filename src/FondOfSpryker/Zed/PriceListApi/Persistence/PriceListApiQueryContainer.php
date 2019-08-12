<?php

namespace FondOfSpryker\Zed\PriceListApi\Persistence;

use Orm\Zed\PriceList\Persistence\FosPriceListQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiPersistenceFactory getFactory()
 */
class PriceListApiQueryContainer extends AbstractQueryContainer implements PriceListApiQueryContainerInterface
{
    /**
     * @return \Orm\Zed\PriceList\Persistence\FosPriceListQuery
     */
    public function queryFind(): FosPriceListQuery
    {
        return $this->getFactory()->getPriceListQuery();
    }
}
