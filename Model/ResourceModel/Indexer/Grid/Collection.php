<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterIndexer\Model\ResourceModel\Indexer\Grid;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Websolute\TransporterIndexer\Model\Indexer\Activity;
use Websolute\TransporterIndexer\Model\ResourceModel\Indexer\Activity\DefaultIndexerActivity;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'activity_id';

    protected function _construct()
    {
        $this->_init(
            Activity::class,
            DefaultIndexerActivity::class
        );
    }
}
