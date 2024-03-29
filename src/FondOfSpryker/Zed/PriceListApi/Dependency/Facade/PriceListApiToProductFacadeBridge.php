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
     * @param array<string> $skus
     *
     * @return array<int>
     */
    public function getProductConcreteIdsByConcreteSkus(array $skus): array
    {
        return $this->productFacade->getProductConcreteIdsByConcreteSkus($skus);
    }
}
