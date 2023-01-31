<?php

namespace FondOfSpryker\Zed\PriceListApi\Communication\Plugin\PriceListApi;

use FondOfSpryker\Zed\PriceListApi\Dependency\Plugin\PriceProductsHydrationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\PriceListApi\PriceListApiConfig getConfig()
 * @method \FondOfSpryker\Zed\PriceListApi\Business\PriceListApiFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface getQueryContainer()
 */
class ProductIdsPriceProductsHydrationPlugin extends AbstractPlugin implements PriceProductsHydrationPluginInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function hydrate(array $priceProductTransfers): array
    {
        return $this->getFacade()->hydratePriceProductsWithProductIds($priceProductTransfers);
    }
}
