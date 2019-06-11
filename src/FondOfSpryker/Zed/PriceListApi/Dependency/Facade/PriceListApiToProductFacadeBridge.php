<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\Facade;

use Spryker\Zed\Product\Business\ProductFacadeInterface;

class PriceListApiToProductFacadeBridge implements PriceListApiToProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct(ProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductAbstractIdBySku(string $sku): ?int
    {
        return $this->productFacade->findProductAbstractIdBySku($sku);
    }

    /**
     * @param string $sku
     *
     * @return int|null
     */
    public function findProductConcreteIdBySku(string $sku): ?int
    {
        return $this->productFacade->findProductConcreteIdBySku($sku);
    }
}
