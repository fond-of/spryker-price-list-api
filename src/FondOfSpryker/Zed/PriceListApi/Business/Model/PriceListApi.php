<?php

namespace FondOfSpryker\Zed\PriceListApi\Business\Model;

use FondOfSpryker\Zed\PriceListApi\Business\Exception\MissingPriceDimensionException;
use FondOfSpryker\Zed\PriceListApi\Business\Mapper\TransferMapperInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerInterface;
use FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\PriceListApiTransfer;
use Generated\Shared\Transfer\PriceListTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Orm\Zed\PriceList\Persistence\Map\FosPriceListTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Map\TableMap;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
use Spryker\Zed\Api\Business\Exception\EntityNotSavedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class PriceListApi implements PriceListApiInterface
{
    use LoggerTrait;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface
     */
    protected $priceListFacade;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Business\Mapper\TransferMapperInterface
     */
    protected $transferMapper;

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
     * @var \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerInterface
     */
    protected $apiQueryBuilderQueryContainer;

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface $connection
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface $priceListFacade
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface $priceProductPriceListFacade
     * @param \FondOfSpryker\Zed\PriceListApi\Business\Mapper\TransferMapperInterface $transferMapper
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerInterface $apiQueryContainer
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerInterface $apiQueryBuilderQueryContainer
     * @param \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface $queryContainer
     * @param \FondOfSpryker\Zed\PriceListApi\Dependency\Plugin\PriceProductHydrationPluginInterface[] $priceProductHydrationPlugins
     */
    public function __construct(
        ConnectionInterface $connection,
        PriceListApiToPriceListFacadeInterface $priceListFacade,
        PriceListApiToPriceProductPriceListFacadeInterface $priceProductPriceListFacade,
        TransferMapperInterface $transferMapper,
        PriceListApiToApiQueryContainerInterface $apiQueryContainer,
        PriceListApiToApiQueryBuilderQueryContainerInterface $apiQueryBuilderQueryContainer,
        PriceListApiQueryContainerInterface $queryContainer,
        array $priceProductHydrationPlugins
    ) {
        $this->connection = $connection;
        $this->priceListFacade = $priceListFacade;
        $this->priceProductPriceListFacade = $priceProductPriceListFacade;
        $this->transferMapper = $transferMapper;
        $this->apiQueryContainer = $apiQueryContainer;
        $this->priceProductHydrationPlugins = $priceProductHydrationPlugins;
        $this->queryContainer = $queryContainer;
        $this->apiQueryBuilderQueryContainer = $apiQueryBuilderQueryContainer;
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
        $data = (array)$apiDataTransfer->getData();
        $priceListApiTransfer = $this->transferMapper->toTransfer($data);

        $priceListTransfer = new PriceListTransfer();
        $priceListTransfer->fromArray($priceListApiTransfer->toArray(), true);

        $priceListTransfer = $this->addPriceList($priceListTransfer);

        return $this->persistPriceListEntries($priceListTransfer, $priceListApiTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $priceListTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotSavedException
     *
     * @return \Generated\Shared\Transfer\PriceListTransfer
     */
    protected function addPriceList(PriceListTransfer $priceListTransfer): PriceListTransfer
    {
        $this->connection->beginTransaction();

        try {
            $priceListTransfer = $this->priceListFacade->createPriceList($priceListTransfer);
        } catch (Throwable $throwable) {
            $this->connection->rollBack();
            throw new EntityNotSavedException(sprintf('Could not save price list: %s', $throwable->getMessage()), ApiConfig::HTTP_CODE_INTERNAL_ERROR);
        }

        $this->connection->commit();

        return $priceListTransfer;
    }

    /**
     * @param int $id
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function update(int $id, ApiDataTransfer $apiDataTransfer): ApiItemTransfer
    {
        $priceListTransfer = $this->getByIdPriceList($id);

        if ($priceListTransfer === null) {
            throw new EntityNotFoundException(sprintf('price list not found: %s', $id));
        }

        $data = (array)$apiDataTransfer->getData();
        $priceListApiTransfer = $this->transferMapper->toTransfer($data);

        $priceListTransfer->fromArray($priceListApiTransfer->toArray(), true);

        $priceListTransfer = $this->updatePriceList($priceListTransfer);

        return $this->persistPriceListEntries($priceListTransfer, $priceListApiTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceListTransfer $priceListTransfer
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotSavedException
     *
     * @return \Generated\Shared\Transfer\PriceListTransfer
     */
    protected function updatePriceList(PriceListTransfer $priceListTransfer): PriceListTransfer
    {
        $this->connection->beginTransaction();

        try {
            $priceListTransfer = $this->priceListFacade->updatePriceList($priceListTransfer);
        } catch (Throwable $throwable) {
            $this->connection->rollBack();
            throw new EntityNotSavedException(sprintf('Could not save price list: %s', $throwable->getMessage()), ApiConfig::HTTP_CODE_INTERNAL_ERROR);
        }

        $this->connection->commit();

        return $priceListTransfer;
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
    protected function persistPriceListEntries(
        PriceListTransfer $priceListTransfer,
        PriceListApiTransfer $priceListApiTransfer
    ): ApiItemTransfer {
        $this->connection->beginTransaction();

        foreach ($priceListApiTransfer->getPriceListEntries() as $priceProductTransfer) {
            try {
                $this->persistPriceProduct($priceListTransfer, $priceProductTransfer);
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
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @throws \FondOfSpryker\Zed\PriceListApi\Business\Exception\MissingPriceDimensionException
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function persistPriceProduct(
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
     * @param int $idPriceList
     *
     * @return \Generated\Shared\Transfer\PriceListTransfer|null
     */
    protected function getByIdPriceList(int $idPriceList): ?PriceListTransfer
    {
        $priceListTransfer = new PriceListTransfer();
        $priceListTransfer->setIdPriceList($idPriceList);

        return $this->priceListFacade->findPriceListById($priceListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @throws
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer): ApiCollectionTransfer
    {
        $query = $this->buildQuery($apiRequestTransfer);
        $collection = $this->transferMapper->toTransferCollection($query->find()->toArray());

        foreach ($collection as $k => $priceListApiTransfer) {
            $collection[$k] = $this->get($priceListApiTransfer->getIdPriceList())->getData();
        }

        $apiCollectionTransfer = $this->apiQueryContainer->createApiCollection($collection);
        $apiCollectionTransfer = $this->addPagination($query, $apiCollectionTransfer, $apiRequestTransfer);

        return $apiCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function buildQuery(ApiRequestTransfer $apiRequestTransfer): ModelCriteria
    {
        $apiQueryBuilderQueryTransfer = $this->buildApiQueryBuilderQuery($apiRequestTransfer);
        $query = $this->queryContainer->queryFind();
        $query = $this->apiQueryBuilderQueryContainer->buildQueryFromRequest($query, $apiQueryBuilderQueryTransfer);

        return $query;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiQueryBuilderQueryTransfer
     */
    protected function buildApiQueryBuilderQuery(ApiRequestTransfer $apiRequestTransfer): ApiQueryBuilderQueryTransfer
    {
        $columnSelectionTransfer = $this->buildColumnSelection();
        $apiQueryBuilderQueryTransfer = (new ApiQueryBuilderQueryTransfer())
            ->setApiRequest($apiRequestTransfer)
            ->setColumnSelection($columnSelectionTransfer);

        return $apiQueryBuilderQueryTransfer;
    }

    /**
     * @throws
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer
     */
    protected function buildColumnSelection(): PropelQueryBuilderColumnSelectionTransfer
    {
        $columnSelectionTransfer = new PropelQueryBuilderColumnSelectionTransfer();
        $tableColumns = FosPriceListTableMap::getFieldNames(TableMap::TYPE_FIELDNAME);
        foreach ($tableColumns as $columnAlias) {
            $columnTransfer = (new PropelQueryBuilderColumnTransfer())
                ->setName(FosPriceListTableMap::TABLE_NAME . '.' . $columnAlias)
                ->setAlias($columnAlias);
            $columnSelectionTransfer->addTableColumn($columnTransfer);
        }

        return $columnSelectionTransfer;
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\ApiCollectionTransfer $apiCollectionTransfer
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Generated\Shared\Transfer\ApiCollectionTransfer
     */
    protected function addPagination(
        ModelCriteria $query,
        ApiCollectionTransfer $apiCollectionTransfer,
        ApiRequestTransfer $apiRequestTransfer
    ): ApiCollectionTransfer {
        $query->setOffset(0)
            ->setLimit(-1);
        $total = $query->count();
        $page = $apiRequestTransfer->getFilter()->getLimit() ? ($apiRequestTransfer->getFilter()->getOffset() / $apiRequestTransfer->getFilter()->getLimit() + 1) : 1;
        $pageTotal = ($total && $apiRequestTransfer->getFilter()->getLimit()) ? (int)ceil($total / $apiRequestTransfer->getFilter()->getLimit()) : 1;
        if ($page > $pageTotal) {
            throw new NotFoundHttpException('Out of bounds.', null, ApiConfig::HTTP_CODE_NOT_FOUND);
        }
        $apiPaginationTransfer = (new ApiPaginationTransfer())
            ->setItemsPerPage($apiRequestTransfer->getFilter()->getLimit())
            ->setPage($page)
            ->setTotal($total)
            ->setPageTotal($pageTotal);
        $apiCollectionTransfer->setPagination($apiPaginationTransfer);

        return $apiCollectionTransfer;
    }

    /**
     * @param int $idPriceList
     *
     * @throws \Spryker\Zed\Api\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get(int $idPriceList): ApiItemTransfer
    {
        $priceListTransfer = $this->getByIdPriceList($idPriceList);

        if ($priceListTransfer === null) {
            throw new EntityNotFoundException(sprintf('Price list not found for id %s', $idPriceList));
        }

        return $this->apiQueryContainer->createApiItem($priceListTransfer, $idPriceList);
    }
}
