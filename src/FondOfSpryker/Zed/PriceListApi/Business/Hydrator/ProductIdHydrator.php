<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Hydrator;

use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface;
use Generated\Shared\Transfer\PriceProductTransfer;

class ProductIdHydrator implements ProductIdHydratorInterface
{
    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface $productFacade
     */
    public function __construct(PriceListApiToProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function hydrate(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        if ($priceProductTransfer->getIdProduct() === null && $priceProductTransfer->getSkuProduct() !== null) {
            $idProduct = $this->productFacade->findProductConcreteIdBySku($priceProductTransfer->getSkuProduct());
            return $priceProductTransfer->setIdProduct($idProduct);
        }

        if ($priceProductTransfer->getIdProductAbstract() === null && $priceProductTransfer->getSkuProductAbstract() !== null) {
            $idProduct = $this->productFacade->findProductAbstractIdBySku($priceProductTransfer->getSkuProductAbstract());
            return $priceProductTransfer->setIdProductAbstract($idProduct);
        }

        return $priceProductTransfer;
    }
}
