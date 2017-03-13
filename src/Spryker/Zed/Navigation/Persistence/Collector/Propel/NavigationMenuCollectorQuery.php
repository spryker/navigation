<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence\Collector\Propel;

use Orm\Zed\Navigation\Persistence\Map\SpyNavigationTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Collector\Persistence\Collector\AbstractPropelCollectorQuery;

class NavigationMenuCollectorQuery extends AbstractPropelCollectorQuery
{

    const FIELD_ID_NAVIGATION = 'id_navigation';
    const FIELD_KEY = 'key';

    /**
     * @return void
     */
    protected function prepareQuery()
    {
        $this->touchQuery->addJoin(
            SpyTouchTableMap::COL_ITEM_ID,
            SpyNavigationTableMap::COL_ID_NAVIGATION,
            Criteria::INNER_JOIN
        );

        $this->touchQuery->withColumn(SpyNavigationTableMap::COL_ID_NAVIGATION, self::FIELD_ID_NAVIGATION);
        $this->touchQuery->withColumn(SpyNavigationTableMap::COL_KEY, self::FIELD_KEY);

        // TODO: fix duplicated entries of collected items
//        $this->touchQuery->addGroupByColumn(SpyTouchTableMap::COL_ID_TOUCH);
//        $this->touchQuery->addGroupByColumn(SpyTouchTableMap::COL_ITEM_EVENT);
//        $this->touchQuery->addGroupByColumn(SpyTouchTableMap::COL_ITEM_TYPE);
//        $this->touchQuery->addGroupByColumn(SpyTouchTableMap::COL_ITEM_ID);
//        $this->touchQuery->addGroupByColumn(SpyTouchTableMap::COL_TOUCHED);
//        $this->touchQuery->addGroupByColumn(SpyNavigationTableMap::COL_ID_NAVIGATION);
//        $this->touchQuery->addGroupByColumn(SpyNavigationTableMap::COL_KEY);
    }

}
