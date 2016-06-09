<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface;

class ItemTax implements OrderAmountAggregatorInterface
{

    /**
     * @var \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface
     */
    protected $taxFacade;

    /**
     * @var float
     */
    protected $roundingError;

    /**
     * @param \Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxInterface $taxFacade
     */
    public function __construct(SalesAggregatorToTaxInterface $taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->assertItemTaxRequirements($orderTransfer);
        $this->addTaxAmountToTaxableItems($orderTransfer->getItems());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $taxableItems
     *
     * @return void
     */
    protected function addTaxAmountToTaxableItems(\ArrayObject $taxableItems)
    {
        foreach ($taxableItems as $itemTransfer) {
            $unitTaxAmount = $this->calculateTaxAmount(
                $itemTransfer->getUnitGrossPrice(),
                $itemTransfer->getTaxRate()
            );

            $sumTaxAmount = $this->calculateTaxAmount(
                $itemTransfer->getSumTaxAmount(),
                $itemTransfer->getTaxRate()
            );

            $itemTransfer->setUnitTaxAmount($unitTaxAmount);
            $itemTransfer->setSumTaxAmount($sumTaxAmount);
        }

    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function assertItemTaxRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireItems();
    }

    /**
     * @param int $price
     * @param float $taxRate
     *
     * @return float
     */
    protected function calculateTaxAmount($price, $taxRate)
    {
        $taxAmount = $this->taxFacade->getTaxAmountFromGrossPrice($price, $taxRate, false);

        $taxAmount += $this->roundingError;

        $taxAmountRounded = round($taxAmount, 4);
        $this->roundingError = $taxAmount - $taxAmountRounded;

        return $taxAmountRounded;
    }

}
