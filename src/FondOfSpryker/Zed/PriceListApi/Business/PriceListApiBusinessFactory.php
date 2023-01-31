<?php

namespace FondOfSpryker\Zed\PriceListApi\Business;

use FondOfSpryker\Zed\PriceListApi\Business\Hydrator\PriceProductsHydrator;
use FondOfSpryker\Zed\PriceListApi\Business\Hydrator\PriceProductsHydratorInterface;
use FondOfSpryker\Zed\PriceListApi\Business\Mapper\TransferMapper;
use FondOfSpryker\Zed\PriceListApi\Business\Mapper\TransferMapperInterface;
use FondOfSpryker\Zed\PriceListApi\Business\Model\PriceListApi;
use FondOfSpryker\Zed\PriceListApi\Business\Model\PriceListApiInterface;
use FondOfSpryker\Zed\PriceListApi\Business\Validator\PriceListApiValidator;
use FondOfSpryker\Zed\PriceListApi\Business\Validator\PriceListApiValidatorInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerInterface;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerInterface;
use FondOfSpryker\Zed\PriceListApi\PriceListApiDependencyProvider;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \FondOfSpryker\Zed\PriceListApi\PriceListApiConfig getConfig()
 * @method \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiQueryContainerInterface getQueryContainer()
 * @method \FondOfSpryker\Zed\PriceListApi\Persistence\PriceListApiRepositoryInterface getRepository()
 */
class PriceListApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \FondOfSpryker\Zed\PriceListApi\Business\Hydrator\PriceProductsHydratorInterface
     */
    public function createPriceProductsHydrator(): PriceProductsHydratorInterface
    {
        return new PriceProductsHydrator(
            $this->getProductFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListApi\Business\Model\PriceListApiInterface
     */
    public function createProductListApi(): PriceListApiInterface
    {
        return new PriceListApi(
            $this->getPropelConnection(),
            $this->getPriceListFacade(),
            $this->getPriceProductPriceListFacade(),
            $this->createApiDataTransferMapper(),
            $this->getApiQueryContainer(),
            $this->getApiQueryBuilderQueryContainer(),
            $this->getQueryContainer(),
            $this->getPriceProductsHydrationPlugins(),
        );
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListApi\Business\Validator\PriceListApiValidatorInterface
     */
    public function createPriceListApiValidator(): PriceListApiValidatorInterface
    {
        return new PriceListApiValidator();
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListApi\Business\Mapper\TransferMapperInterface
     */
    protected function createApiDataTransferMapper(): TransferMapperInterface
    {
        return new TransferMapper();
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeInterface
     */
    protected function getProductFacade(): PriceListApiToProductFacadeInterface
    {
        return $this->getProvidedDependency(PriceListApiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeInterface
     */
    protected function getPriceListFacade(): PriceListApiToPriceListFacadeInterface
    {
        return $this->getProvidedDependency(PriceListApiDependencyProvider::FACADE_PRICE_LIST);
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeInterface
     */
    protected function getPriceProductPriceListFacade(): PriceListApiToPriceProductPriceListFacadeInterface
    {
        return $this->getProvidedDependency(PriceListApiDependencyProvider::FACADE_PRICE_PRODUCT_PRICE_LIST);
    }

    /**
     * @return array<\FondOfSpryker\Zed\PriceListApi\Dependency\Plugin\PriceProductsHydrationPluginInterface>
     */
    protected function getPriceProductsHydrationPlugins(): array
    {
        return $this->getProvidedDependency(PriceListApiDependencyProvider::PLUGINS_PRICE_PRODUCTS_HYDRATION);
    }

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getPropelConnection(): ConnectionInterface
    {
        return $this->getProvidedDependency(PriceListApiDependencyProvider::PROPEL_CONNECTION);
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerInterface
     */
    protected function getApiQueryContainer(): PriceListApiToApiQueryContainerInterface
    {
        return $this->getProvidedDependency(PriceListApiDependencyProvider::QUERY_CONTAINER_API);
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerInterface
     */
    protected function getApiQueryBuilderQueryContainer(): PriceListApiToApiQueryBuilderQueryContainerInterface
    {
        return $this->getProvidedDependency(PriceListApiDependencyProvider::QUERY_CONTAINER_API_QUERY_BUILDER);
    }
}
