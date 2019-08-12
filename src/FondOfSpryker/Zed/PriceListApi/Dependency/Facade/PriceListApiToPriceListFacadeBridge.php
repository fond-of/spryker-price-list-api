<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\Facade;

use FondOfSpryker\Zed\PriceList\Business\PriceListFacadeInterface;
use Generated\Shared\Transfer\PriceListTransfer;

class PriceListApiToPriceListFacadeBridge implements PriceListApiToPriceListFacadeInterface
{
    /**
     * @var \FondOfSpryker\Zed\PriceList\Business\PriceListFacadeInterface
     */
    protected $priceListFacade;

    /**
     * @param \FondOfSpryker\Zed\PriceList\Business\PriceListFacadeInterface $priceListFacade
     */
    public function __construct(PriceListFacadeInterface $priceListFacade)
    {
        $this->priceListFacade = $priceListFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $priceListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceListTransfer|null
     */
    public function findPriceListByName(PriceListTransfer $priceListTransfer): ?PriceListTransfer
    {
        return $this->priceListFacade->findPriceListByName($priceListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $priceListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceListTransfer
     */
    public function persistPriceList(PriceListTransfer $priceListTransfer): PriceListTransfer
    {
        return $this->priceListFacade->persistPriceList($priceListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $priceListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceListTransfer|null
     */
    public function findPriceListById(PriceListTransfer $priceListTransfer): ?PriceListTransfer
    {
        return $this->priceListFacade->findPriceListById($priceListTransfer);
    }
}
