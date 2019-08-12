<?php

namespace FondOfSpryker\Zed\PriceListApi\Persistence;

use Orm\Zed\PriceList\Persistence\FosPriceListQuery;

interface PriceListApiQueryContainerInterface
{
    /**
     * @return \Orm\Zed\PriceList\Persistence\FosPriceListQuery
     */
    public function queryFind(): FosPriceListQuery;
}
