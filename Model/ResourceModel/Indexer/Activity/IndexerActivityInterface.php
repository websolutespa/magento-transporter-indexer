<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterIndexer\Model\ResourceModel\Indexer\Activity;

interface IndexerActivityInterface
{
    /**
     * Reindex temporary (price result data) for all activity
     *
     * @return $this
     */
    public function reindexAll(): IndexerActivityInterface;

    /**
     * Reindex temporary (price result data) for defined activities
     *
     * @param int|array $entityIds
     * @return $this
     */
    public function reindexEntity($entityIds): IndexerActivityInterface;
}
