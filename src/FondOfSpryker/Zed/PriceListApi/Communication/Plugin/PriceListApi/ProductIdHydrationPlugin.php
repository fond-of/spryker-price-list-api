<?php

namespace FondOfSpryker\Zed\PriceListApi\Communication\Plugin\PriceListApi;

use FondOfSpryker\Zed\PriceListApi\Dependency\Plugin\PriceProductHydrationPluginInterface;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\PriceListApi\PriceListApiConfig getConfig()
 * @method \FondOfSpryker\Zed\PriceListApi\Business\PriceListApiFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface getQueryContainer()
 */
class ProductIdHydrationPlugin extends AbstractPlugin implements PriceProductHydrationPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function hydrate(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->getFacade()->hydrateProductId($priceProductTransfer);
    }
}
