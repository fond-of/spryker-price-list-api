<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Hydrator;

use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiRepositoryInterface;

class PriceProductsHydrator implements PriceProductsHydratorInterface
{
    protected const GROUPED_KEY_ABSTRACT = 'abstract';
    protected const GROUPED_KEY_CONCRETE = 'concrete';

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiRepositoryInterface
     */
    protected $repository;

    /**
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface $productFacade
     */
    public function __construct(
        PriceListApiToProductFacadeInterface $productFacade,
        PriceListApiRepositoryInterface $repository
    ) {
        $this->productFacade = $productFacade;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function hydrate(array $priceProductTransfers): array
    {
        $groupedPriceProductTransfers = $this->groupPriceProducts($priceProductTransfers);
        $groupedPriceProductTransfers = $this->hydrateWithProductAbstractIds($groupedPriceProductTransfers);
        $this->hydrateWithProductConcreteIds($groupedPriceProductTransfers);

        return $priceProductTransfers;
    }

    /**
     * @param array<string, array<string, \Generated\Shared\Transfer\PriceProductTransfer>> $groupedPriceProductTransfers
     *
     * @return array<string, array<string, \Generated\Shared\Transfer\PriceProductTransfer>>
     */
    protected function hydrateWithProductAbstractIds(array $groupedPriceProductTransfers): array
    {
        $skus = array_keys($groupedPriceProductTransfers[static::GROUPED_KEY_ABSTRACT]);

        if (empty($skus)) {
            return $groupedPriceProductTransfers;
        }

        $productIds = $this->repository->getProductAbstractIdsByAbstractSkus($skus);

        foreach ($productIds as $sku => $productId) {
            if (empty($groupedPriceProductTransfers[static::GROUPED_KEY_ABSTRACT][$sku])) {
                continue;
            }

            $groupedPriceProductTransfers[static::GROUPED_KEY_ABSTRACT][$sku]->setIdProductAbstract($productId);
        }

        return $groupedPriceProductTransfers;
    }

    /**
     * @param array<string, array<string, \Generated\Shared\Transfer\PriceProductTransfer>> $groupedPriceProductTransfers
     *
     * @return array<string, array<string, \Generated\Shared\Transfer\PriceProductTransfer>>
     */
    protected function hydrateWithProductConcreteIds(array $groupedPriceProductTransfers): array
    {
        $skus = array_keys($groupedPriceProductTransfers[static::GROUPED_KEY_CONCRETE]);

        if (empty($skus)) {
            return $groupedPriceProductTransfers;
        }

        $productIds = $this->productFacade->getProductConcreteIdsByConcreteSkus($skus);

        foreach ($productIds as $sku => $productId) {
            if (empty($groupedPriceProductTransfers[static::GROUPED_KEY_CONCRETE][$sku])) {
                continue;
            }

            $groupedPriceProductTransfers[static::GROUPED_KEY_CONCRETE][$sku]->setIdProduct($productId);
        }

        return $groupedPriceProductTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return array<string, array<string, \Generated\Shared\Transfer\PriceProductTransfer>>
     */
    protected function groupPriceProducts(array $priceProductTransfers): array
    {
        $groupedPriceProductTransfers = [
            static::GROUPED_KEY_ABSTRACT => [],
            static::GROUPED_KEY_CONCRETE => [],
        ];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $sku = $priceProductTransfer->getSkuProduct();

            if ($sku !== null && $priceProductTransfer->getIdProduct() === null) {
                $groupedPriceProductTransfers[static::GROUPED_KEY_CONCRETE][$sku] = $priceProductTransfer;

                continue;
            }

            $sku = $priceProductTransfer->getSkuProductAbstract();

            if ($sku !== null && $priceProductTransfer->getIdProductAbstract() === null) {
                $groupedPriceProductTransfers[static::GROUPED_KEY_ABSTRACT][$sku] = $priceProductTransfer;
            }
        }

        return $groupedPriceProductTransfers;
    }
}
