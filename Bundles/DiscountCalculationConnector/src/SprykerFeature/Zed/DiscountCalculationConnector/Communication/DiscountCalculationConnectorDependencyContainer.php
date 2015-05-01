<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Communication;

use SprykerFeature\Zed\Calculation\Business\Model\StackExecutor;
use SprykerFeature\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorFacade;
use SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade\DiscountFacadeInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;

/**
 * Class DiscountCalculationConnectorDependencyContainer
 * @package SprykerFeature\Zed\DiscountCalculationConnector\Communication
 */
class DiscountCalculationConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return DiscountFacadeInterface
     */
    public function getDiscountFacade()
    {
        return $this->getLocator()->discount()->facade();
    }

    /**
     * @return DiscountCalculationConnectorFacade
     */
    public function getDiscountCalculationFacade()
    {
        return $this->getLocator()->discountCalculationConnector()->facade();
    }
}