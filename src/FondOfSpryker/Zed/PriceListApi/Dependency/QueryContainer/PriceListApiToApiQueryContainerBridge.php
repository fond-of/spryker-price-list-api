<?php

namespace FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer;

use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class PriceListApiToApiQueryContainerBridge implements PriceListApiToApiQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface
     */
    protected $apiQueryContainer;

    /**
     * @param \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface $apiQueryContainer
     */
    public function __construct($apiQueryContainer)
    {
        $this->apiQueryContainer = $apiQueryContainer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param int|null $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItem(AbstractTransfer $data, ?int $id = null): ApiItemTransfer
    {
        return $this->apiQueryContainer->createApiItem($data, $id);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function createApiCollection(array $data): ApiCollectionTransfer
    {
        return $this->apiQueryContainer->createApiCollection($data);
    }
}
