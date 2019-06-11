<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Model;

use FondOfSpryker\Zed\PriceListApi\Business\Exception\MissingPriceDimensionException;
use FondOfSpryker\Zed\PriceListApi\Business\Mapper\ApiDataTransferMapperInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerInterface;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\PriceListApiTransfer;
use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\Api\Business\Exception\EntityNotSavedException;
use Throwable;

class PriceListApi implements PriceListApiInterface
{
    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface
     */
    protected $priceListFacade;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Business\Mapper\ApiDataTransferMapperInterface
     */
    protected $apiDataTransferMapper;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface
     */
    protected $priceProductPriceListFacade;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\Plugin\PriceProductHydrationPluginInterface[]
     */
    protected $priceProductHydrationPlugins;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerInterface
     */
    protected $apiQueryContainer;

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface $priceListFacade
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface $priceProductPriceListFacade
     * @param \FondOfSpryker\Zed\PriceListApi\Business\Mapper\ApiDataTransferMapperInterface $apiDataTransferMapper
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerInterface $apiQueryContainer
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\Plugin\PriceProductHydrationPluginInterface[] $priceProductHydrationPlugins
     */
    public function __construct(
        ConnectionInterface $connection,
        PriceListApiToPriceListFacadeInterface $priceListFacade,
        PriceListApiToPriceProductPriceListFacadeInterface $priceProductPriceListFacade,
        ApiDataTransferMapperInterface $apiDataTransferMapper,
        PriceListApiToApiQueryContainerInterface $apiQueryContainer,
        array $priceProductHydrationPlugins
    ) {
        $this->connection = $connection;
        $this->priceListFacade = $priceListFacade;
        $this->priceProductPriceListFacade = $priceProductPriceListFacade;
        $this->apiDataTransferMapper = $apiDataTransferMapper;
        $this->apiQueryContainer = $apiQueryContainer;
        $this->priceProductHydrationPlugins = $priceProductHydrationPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @throws
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer): ApiItemTransfer
    {
        $priceListApiTransfer = $this->apiDataTransferMapper->toPriceListApiTransfer($apiDataTransfer);

        $priceListTransfer = new PriceListTransfer();
        $priceListTransfer->fromArray($priceListApiTransfer->toArray(), true);

        return $this->persist($priceListTransfer, $priceListApiTransfer);
    }

    /**
     * @param string $id
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function update(string $id, ApiDataTransfer $apiDataTransfer): ApiItemTransfer
    {
        $priceListTransfer = $this->getByName($id);

        if ($priceListTransfer === null) {
            throw new EntityNotFoundException(sprintf('Price list not found: %s', $id));
        }

        $priceListApiTransfer = $this->apiDataTransferMapper->toPriceListApiTransfer($apiDataTransfer);
        $priceListTransfer->fromArray($priceListApiTransfer->toArray(), true);

        return $this->persist($priceListTransfer, $priceListApiTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $priceListTransfer
     * @param \Generated\Shared\Transfer\PriceListApiTransfer $priceListApiTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotSavedException
     * @throws \FondOfSpryker\Zed\PriceListApi\Business\Exception\MissingPriceDimensionException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    protected function persist(
        PriceListTransfer $priceListTransfer,
        PriceListApiTransfer $priceListApiTransfer
    ): ApiItemTransfer {
        $this->connection->beginTransaction();

        try {
            $priceListTransfer = $this->priceListFacade->persistPriceList($priceListTransfer);

            foreach ($priceListApiTransfer->getPriceListEntries() as $priceProductTransfer) {
                if ($priceProductTransfer->getPriceDimension() === null) {
                    throw new MissingPriceDimensionException('Price dimension is missing.', ApiConfig::HTTP_CODE_INTERNAL_ERROR);
                }

                $priceProductTransfer->getPriceDimension()->setIdPriceList($priceListTransfer->getIdPriceList());

                $this->persistPriceProductTransfer($priceProductTransfer);
            }

            $this->connection->commit();
        } catch (Throwable $throwable) {
            $this->connection->rollBack();
            throw new EntityNotSavedException(sprintf('Could not save price list: %s', $throwable->getMessage()), ApiConfig::HTTP_CODE_INTERNAL_ERROR);
        }

        return $this->apiQueryContainer->createApiItem($priceListApiTransfer, $priceListTransfer->getIdPriceList());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistPriceProductTransfer(
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        foreach ($this->priceProductHydrationPlugins as $priceProductHydrationPlugin) {
            $priceProductTransfer = $priceProductHydrationPlugin->hydrate($priceProductTransfer);
        }

        $this->priceProductPriceListFacade->savePriceProductPriceList($priceProductTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\PriceListTransfer|null
     */
    protected function getByName(string $name): ?PriceListTransfer
    {
        $priceListTransfer = new PriceListTransfer();
        $priceListTransfer->setName($name);

        return $this->priceListFacade->findPriceListByName($priceListTransfer);
    }
}
