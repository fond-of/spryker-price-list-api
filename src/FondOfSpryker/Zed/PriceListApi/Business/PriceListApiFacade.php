<?php

namespace FondOfSpryker\Zed\PriceListApi\Business;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \FondOfSpryker\Zed\PriceListApi\Business\PriceListApiBusinessFactory getFactory()
 */
class PriceListApiFacade extends AbstractFacade implements PriceListApiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function addPriceList(ApiDataTransfer $apiDataTransfer): ApiItemTransfer
    {
        return $this->getFactory()->createProductListApi()->add($apiDataTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $id
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function updatePriceList(int $id, ApiDataTransfer $apiDataTransfer): ApiItemTransfer
    {
        return $this->getFactory()->createProductListApi()->update($id, $apiDataTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function hydrateProductId(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->getFactory()->createProductIdHydrator()->hydrate($priceProductTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idPriceList
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function getPriceList(int $idPriceList): ApiItemTransfer
    {
        return $this->getFactory()->createProductListApi()->get($idPriceList);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function findPriceLists(ApiRequestTransfer $apiRequestTransfer): ApiCollectionTransfer
    {
        return $this->getFactory()->createProductListApi()->find($apiRequestTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return array
     */
    public function validate(ApiDataTransfer $apiDataTransfer): array
    {
        return $this->getFactory()->createPriceListApiValidator()->validate($apiDataTransfer);
    }
}
