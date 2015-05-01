<?php

namespace SprykerFeature\Yves\ProductExporter\ResourceCreator;

use Silex\Application;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Shared\Application\Communication\ControllerServiceBuilder;
use SprykerEngine\Yves\Kernel\Communication\BundleControllerAction;
use SprykerEngine\Yves\Kernel\Communication\Controller\BundleControllerActionRouteNameResolver;
use SprykerEngine\Yves\Kernel\Communication\ControllerLocator;
use SprykerFeature\Yves\ProductExporter\Builder\FrontendProductBuilderInterface;
use SprykerFeature\Yves\FrontendExporter\Creator\ResourceCreatorInterface;

class ProductResourceCreator implements ResourceCreatorInterface
{
    /**
     * @var FrontendProductBuilderInterface
     */
    protected $productBuilder;

    /**
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @param FrontendProductBuilderInterface $productBuilder
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        FrontendProductBuilderInterface $productBuilder,
        LocatorLocatorInterface $locator
    ) {
        $this->productBuilder = $productBuilder;
        $this->locator = $locator;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'product';
    }

    /**
     * @param Application $app
     * @param array $data
     * @return array
     */
    public function createResource(Application $app, array $data)
    {
        $bundleControllerAction = new BundleControllerAction('Product', 'Product', 'detail');
        $controllerResolver = new ControllerLocator($bundleControllerAction);
        $routeResolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $service = (new ControllerServiceBuilder())->createServiceForController(
            $app,
            $this->locator,
            $bundleControllerAction,
            $controllerResolver,
            $routeResolver
        );

        $product = $this->productBuilder->buildProduct($data);

        return [
            '_controller' => $service,
            '_route' => $routeResolver->resolve(),
            'product' => $product
        ];
    }
}
