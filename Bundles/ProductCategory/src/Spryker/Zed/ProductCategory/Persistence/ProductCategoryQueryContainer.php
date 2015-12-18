<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyLocalizedAbstractProductAttributesTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;

/**
 * @method ProductCategoryPersistenceFactory getFactory()
 */
class ProductCategoryQueryContainer extends AbstractQueryContainer implements ProductCategoryQueryContainerInterface
{

    const COL_CATEGORY_NAME = 'category_name';

    /**
     * @param ModelCriteria $query
     * @param LocaleTransfer $locale
     * @param bool $excludeDirectParent
     * @param bool $excludeRoot
     *
     * @return ModelCriteria
     */
    public function expandProductCategoryPathQuery(
        ModelCriteria $query,
        LocaleTransfer $locale,
        $excludeDirectParent = true,
        $excludeRoot = true
    ) {
        return $this->getFactory()
            ->createProductCategoryPathQueryExpander($locale)
            ->expandQuery($query, $excludeDirectParent, $excludeRoot);
    }

    /**
     * @return SpyProductCategoryQuery
     */
    protected function queryProductCategoryMappings()
    {
        return $this->getFactory()->createProductCategoryQuery();
    }

    /**
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingsByCategoryId($idCategory)
    {
        return $this->getFactory()
            ->createProductCategoryQuery()
            ->filterByFkCategory($idCategory);
    }

    /**
     * @param int $idCategory
     * @param int $idProductAbstract
     *
     * @return SpyProductCategoryQuery
     */
    public function queryProductCategoryMappingByIds($idCategory, $idProductAbstract)
    {
        $query = $this->queryProductCategoryMappings();
        $query
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkCategory($idCategory);

        return $query;
    }

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingBySkuAndCategoryName($sku, $categoryName, LocaleTransfer $locale)
    {
        $query = $this->queryProductCategoryMappings();
        $query
            ->useSpyProductAbstractQuery()
                ->filterBySku($sku)
            ->endUse()
            ->useSpyCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($locale->getIdLocale())
                    ->filterByName($categoryName)
                ->endUse()
            ->endUse();

        return $query;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return SpyProductCategoryQuery
     */
    public function queryLocalizedProductCategoryMappingByIdProduct($idProductAbstract)
    {
        $query = $this->queryProductCategoryMappings();
        $query->filterByFkProductAbstract($idProductAbstract);

        return $query;
    }

    /**
     * @param int $idCategory
     * @param LocaleTransfer $locale
     *
     * @return SpyProductCategoryQuery
     */
    public function queryProductsByCategoryId($idCategory, LocaleTransfer $locale)
    {
        return $this->queryProductCategoryMappings()
            ->innerJoinSpyProductAbstract()
            ->addJoin(
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyLocalizedAbstractProductAttributesTableMap::COL_FK_PRODUCT_ABSTRACT,
                Criteria::INNER_JOIN
            )
            ->addJoin(
                SpyLocalizedAbstractProductAttributesTableMap::COL_FK_LOCALE,
                SpyLocaleTableMap::COL_ID_LOCALE,
                Criteria::INNER_JOIN
            )
            ->addAnd(
                SpyLocaleTableMap::COL_ID_LOCALE,
                $locale->getIdLocale(),
                Criteria::EQUAL
            )
            ->addAnd(
                SpyLocaleTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            )
            ->withColumn(
                SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
                'name'
            )
            ->withColumn(
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                'id_product_abstract'
            )
            ->withColumn(
                SpyProductAbstractTableMap::COL_ATTRIBUTES,
                'abstract_attributes'
            )
            ->withColumn(
                SpyLocalizedAbstractProductAttributesTableMap::COL_ATTRIBUTES,
                'abstract_localized_attributes'
            )
            ->withColumn(
                SpyProductAbstractTableMap::COL_SKU,
                'sku'
            )
            ->withColumn(
                SpyProductCategoryTableMap::COL_PRODUCT_ORDER,
                'product_order'
            )
            ->withColumn(
                SpyProductCategoryTableMap::COL_ID_PRODUCT_CATEGORY,
                'id_product_category'
            )
            ->filterByFkCategory($idCategory)
            ->orderByFkProductAbstract();
    }

    /**
     * @param $term
     * @param LocaleTransfer $locale
     * @param int $idExcludedCategory null
     *
     * @return SpyProductAbstractQuery
     */
    public function queryAbstractProductsBySearchTerm($term, LocaleTransfer $locale, $idExcludedCategory = null)
    {
        $idExcludedCategory = (int) $idExcludedCategory;
        $query = SpyProductAbstractQuery::create();

        $query->addJoin(
            SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            SpyLocalizedAbstractProductAttributesTableMap::COL_FK_PRODUCT_ABSTRACT,
            Criteria::INNER_JOIN
        )
        ->addJoin(
            SpyLocalizedAbstractProductAttributesTableMap::COL_FK_LOCALE,
            SpyLocaleTableMap::COL_ID_LOCALE,
            Criteria::INNER_JOIN
        )
        ->addAnd(
            SpyLocaleTableMap::COL_ID_LOCALE,
            $locale->getIdLocale(),
            Criteria::EQUAL
        )
        ->addAnd(
            SpyLocaleTableMap::COL_IS_ACTIVE,
            true,
            Criteria::EQUAL
        )
        ->withColumn(
            SpyLocalizedAbstractProductAttributesTableMap::COL_NAME,
            'name'
        )
        ->withColumn(
            SpyProductAbstractTableMap::COL_ATTRIBUTES,
            'abstract_attributes'
        )
        ->withColumn(
            SpyLocalizedAbstractProductAttributesTableMap::COL_ATTRIBUTES,
            'abstract_localized_attributes'
        );

        $query->groupByAttributes();
        $query->groupByIdProductAbstract();

        if (trim($term) !== '') {
            $term = '%' . mb_strtoupper($term) . '%';

            $query->where('UPPER(' . SpyProductAbstractTableMap::COL_SKU . ') LIKE ?', $term, \PDO::PARAM_STR)
                ->_or()
                ->where('UPPER(' . SpyLocalizedAbstractProductAttributesTableMap::COL_NAME . ') LIKE ?', $term, \PDO::PARAM_STR);
        }

        if ($idExcludedCategory > 0) {
            $query
                ->addJoin(
                    SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                    SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT,
                    Criteria::INNER_JOIN
                )
                ->_and()
                ->where(SpyProductCategoryTableMap::COL_FK_CATEGORY . ' <> ?', $idExcludedCategory, \PDO::PARAM_INT);
        }

        return $query;
    }

    /**
     * @param int $idCategory
     * @param int $idProductAbstract
     *
     * @return SpyProductQuery
     */
    public function queryProductCategoryPreconfig($idCategory, $idProductAbstract)
    {
        return SpyProductQuery::create()
            ->filterByFkProductAbstract($idProductAbstract)
            ->addAnd(
                SpyProductTableMap::COL_IS_ACTIVE,
                true,
                Criteria::EQUAL
            );
    }

}
