<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductOptionQueryContainerInterface
{
    /**
     * @param int $idProductOptionGroup
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupByIdProductOptionGroup($idProductOptionGroup);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractBySku($sku);

    /**
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionByValueId($idProductOptionValue);

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValueBySku($sku);

    /**
     * @api
     *
     * @param int[] $allIdOptionValueUsages
     * @param string $countryIso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryTaxSetByIdProductOptionValueUsagesAndCountryIso2Code($allIdOptionValueUsages, $countryIso2Code);

    /**
     * @param int $idProductOptionGroup
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroupQuery
     */
    public function queryProductOptionGroupById($idProductOptionGroup);

    /**
     * @param int $idProductOptionGroup
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductAbstractProductOptionGroupQuery
     */
    public function queryAbstractProductsByOptionGroupId($idProductOptionGroup, LocaleTransfer $localeTransfer);

    /**
     * @api
     *
     * @param string $term
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductsAbstractBySearchTerm($term, LocaleTransfer $localeTransfer);


}
