<?php

namespace FondOfSpryker\Zed\PriceListApi;

use FondOfSpryker\Zed\PriceListApi\Communication\Plugin\PriceListApi\ProductIdsPriceProductsHydrationPlugin;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToApiFacadeBridge;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceListFacadeBridge;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToPriceProductPriceListFacadeBridge;
use FondOfSpryker\Zed\PriceListApi\Dependency\Facade\PriceListApiToProductFacadeBridge;
use FondOfSpryker\Zed\PriceListApi\Dependency\QueryContainer\PriceListApiToApiQueryBuilderQueryContainerBridge;
use Orm\Zed\PriceList\Persistence\FosPriceListQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Propel\Runtime\Propel;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class PriceListApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRICE_LIST = 'FACADE_PRICE_LIST';

    /**
     * @var string
     */
    public const FACADE_PRICE_PRODUCT_PRICE_LIST = 'FACADE_PRICE_PRODUCT_PRICE_LIST';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const PLUGINS_PRICE_PRODUCTS_HYDRATION = 'PLUGINS_PRICE_PRODUCTS_HYDRATION';

    /**
     * @var string
     */
    public const PROPEL_CONNECTION = 'PROPEL_CONNECTION';

    /**
     * @var string
     */
    public const FACADE_API = 'FACADE_API';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRICE_LIST = 'PROPEL_QUERY_PRICE_LIST';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_ABSTRACT = 'PROPEL_QUERY_ABSTRACT_PRODUCT';

    /**
     * @var string
     */
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
        $container = $this->provideApiFacade($container);

        return $this->provideApiQueryBuilderQueryContainer($container);
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

        return $this->addProductAbstractPropelQuery($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceListFacade(Container $container): Container
    {
        $container[static::FACADE_PRICE_LIST] = static function (Container $container) {
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
        $container[static::FACADE_PRICE_PRODUCT_PRICE_LIST] = static function (Container $container) {
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
        $container[static::FACADE_PRODUCT] = static function (Container $container) {
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
        $container[static::PROPEL_CONNECTION] = static function () {
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
        $self = $this;

        $container[static::PLUGINS_PRICE_PRODUCTS_HYDRATION] = static function () use ($self) {
            return $self->getPriceProductsHydrationPlugins();
        };

        return $container;
    }

    /**
     * @return array<\FondOfSpryker\Zed\PriceListApi\Dependency\Plugin\PriceProductsHydrationPluginInterface>
     */
    protected function getPriceProductsHydrationPlugins(): array
    {
        return [
            new ProductIdsPriceProductsHydrationPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideApiFacade(Container $container): Container
    {
        $container[static::FACADE_API] = static function (Container $container) {
            return new PriceListApiToApiFacadeBridge($container->getLocator()->api()->facade());
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
        $container[static::PROPEL_QUERY_PRICE_LIST] = static function () {
            return FosPriceListQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_ABSTRACT] = static function () {
            return SpyProductAbstractQuery::create();
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
        $container[static::QUERY_CONTAINER_API_QUERY_BUILDER] = static function (Container $container) {
            return new PriceListApiToApiQueryBuilderQueryContainerBridge(
                $container->getLocator()->apiQueryBuilder()->queryContainer(),
            );
        };

        return $container;
    }
}
