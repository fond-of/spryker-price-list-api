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
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\Api\Business\Exception\EntityNotSavedException;
use Throwable;

class PriceListApi implements PriceListApiInterface
{
    use LoggerTrait;

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
     * @throws
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotSavedException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    protected function persist(
        PriceListTransfer $priceListTransfer,
        PriceListApiTransfer $priceListApiTransfer
    ): ApiItemTransfer {
        $priceListTransfer = $this->persistPriceList($priceListTransfer);

        $this->connection->beginTransaction();

        foreach ($priceListApiTransfer->getPriceListEntries() as $priceProductTransfer) {
            try {
                $this->persistPriceProductTransfer($priceListTransfer, $priceProductTransfer);
            } catch (Throwable $throwable) {
                $this->connection->rollBack();
                throw new EntityNotSavedException(sprintf('Could not save price list entries: %s', $throwable->getMessage()), ApiConfig::HTTP_CODE_INTERNAL_ERROR);
            }
        }

        $this->connection->commit();

        return $this->apiQueryContainer->createApiItem($priceListApiTransfer, $priceListTransfer->getIdPriceList());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $priceListTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotSavedException
     *
     * @return \Generated\Shared\Transfer\PriceListTransfer
     */
    protected function persistPriceList(PriceListTransfer $priceListTransfer): PriceListTransfer
    {
        $this->connection->beginTransaction();

        try {
            $priceListTransfer = $this->priceListFacade->persistPriceList($priceListTransfer);
        } catch (Throwable $throwable) {
            $this->connection->rollBack();
            throw new EntityNotSavedException(sprintf('Could not save price list: %s', $throwable->getMessage()), ApiConfig::HTTP_CODE_INTERNAL_ERROR);
        }

        $this->connection->commit();

        return $priceListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $priceListTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @throws \FondOfSpryker\Zed\PriceListApi\Business\Exception\MissingPriceDimensionException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistPriceProductTransfer(
        PriceListTransfer $priceListTransfer,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        if ($priceProductTransfer->getPriceDimension() === null) {
            throw new MissingPriceDimensionException('Price dimension is missing.', ApiConfig::HTTP_CODE_INTERNAL_ERROR);
        }

        $priceProductTransfer->getPriceDimension()->setIdPriceList($priceListTransfer->getIdPriceList());

        foreach ($this->priceProductHydrationPlugins as $priceProductHydrationPlugin) {
            $priceProductTransfer = $priceProductHydrationPlugin->hydrate($priceProductTransfer);
        }

        if ($priceProductTransfer->getIdProductAbstract() === null && $priceProductTransfer->getIdProduct() === null) {
            return $priceProductTransfer;
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
