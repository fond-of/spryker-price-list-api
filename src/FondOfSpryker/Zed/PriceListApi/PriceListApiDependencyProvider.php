<?php

namespace FondOfSpryker\Zed\PriceListApi;

use FondOfSpryker\Zed\PriceListApi\Communication\Plugin\PriceListApi\ProductIdHydrationPlugin;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeBridge;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeBridge;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeBridge;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerBridge;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryContainerBridge;
use Orm\Zed\PriceList\Persistence\FosPriceListQuery;
use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class PriceListApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRICE_LIST = 'FACADE_PRICE_LIST';
    public const FACADE_PRICE_PRODUCT_PRICE_LIST = 'FACADE_PRICE_PRODUCT_PRICE_LIST';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const PLUGINS_PRICE_PRODUCT_HYDRATION = 'PLUGINS_PRICE_PRODUCT_HYDRATION';
    public const PROPEL_CONNECTION = 'PROPEL_CONNECTION';
    public const QUERY_CONTAINER_API = 'QUERY_CONTAINER_API';
    public const PROPEL_QUERY_PRICE_LIST = 'PROPEL_QUERY_PRICE_LIST';
    public const QUERY_CONTAINER_API_QUERY_BUILDER = 'QUERY_CONTAINER_API_QUERY_BUILDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addPriceListFacade($container);
        $container = $this->addPriceProductPriceListFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addPropelCommunication($container);
        $container = $this->addPriceProductHydrationPlugins($container);
        $container = $this->provideApiQueryContainer($container);
        $container = $this->provideApiQueryBuilderQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addPriceListPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceListFacade(Container $container): Container
    {
        $container[static::FACADE_PRICE_LIST] = function (Container $container) {
            return new PriceListApiToPriceListFacadeBridge($container->getLocator()->priceList()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductPriceListFacade(Container $container): Container
    {
        $container[static::FACADE_PRICE_PRODUCT_PRICE_LIST] = function (Container $container) {
            return new PriceListApiToPriceProductPriceListFacadeBridge($container->getLocator()->priceProductPriceList()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new PriceListApiToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelCommunication(Container $container): Container
    {
        $container[static::PROPEL_CONNECTION] = function () {
            return Propel::getConnection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductHydrationPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRICE_PRODUCT_HYDRATION] = function () {
            return $this->getPriceProductHydrationPlugins();
        };

        return $container;
    }

    /**
     * @return \FondOfSpryker\Zed\PriceListApi\Dependency\Plugin\PriceProductHydrationPluginInterface[]
     */
    protected function getPriceProductHydrationPlugins(): array
    {
        return [
            new ProductIdHydrationPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideApiQueryContainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_API] = function (Container $container) {
            return new PriceListApiToApiQueryContainerBridge($container->getLocator()->api()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceListPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRICE_LIST] = function (Container $container) {
            return FosPriceListQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideApiQueryBuilderQueryContainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_API_QUERY_BUILDER] = function (Container $container) {
            return new PriceListApiToApiQueryBuilderQueryContainerBridge(
                $container->getLocator()->apiQueryBuilder()->queryContainer()
            );
        };

        return $container;
    }
}
