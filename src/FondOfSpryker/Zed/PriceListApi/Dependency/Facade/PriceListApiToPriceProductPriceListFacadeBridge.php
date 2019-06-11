<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\Facade;

use FondOfSpryker\Zed\PriceProductPriceList\Business\PriceProductPriceListFacadeInterface;
use Generated\Shared\Transfer\PriceProductTransfer;

class PriceListApiToPriceProductPriceListFacadeBridge implements PriceListApiToPriceProductPriceListFacadeInterface
{
    /**
     * @var \FondOfSpryker\Zed\PriceProductPriceList\Business\PriceProductPriceListFacadeInterface
     */
    protected $priceProductPriceListFacade;

    /**
     * @param \FondOfSpryker\Zed\PriceProductPriceList\Business\PriceProductPriceListFacadeInterface $priceProductPriceListFacade
     */
    public function __construct(PriceProductPriceListFacadeInterface $priceProductPriceListFacade)
    {
        $this->priceProductPriceListFacade = $priceProductPriceListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePriceProductPriceList(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->priceProductPriceListFacade->savePriceProductPriceList($priceProductTransfer);
    }
}
