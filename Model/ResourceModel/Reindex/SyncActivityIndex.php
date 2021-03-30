<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterIndexer\Model\ResourceModel\Reindex;

use Magento\Framework\App\ResourceConnection;
use Websolute\TransporterIndexer\Model\ResourceModel\Indexer\Activity\DefaultIndexerActivity;

class SyncActivityIndex
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @param string $sourceTable
     */
    public function execute(string $sourceTable)
    {
        $destinationTableName = $this->resourceConnection
            ->getTableName(DefaultIndexerActivity::TABLE_NAME);

        $sourceTableName = $this->resourceConnection
            ->getTableName($sourceTable);

        $connection = $this->resourceConnection->getConnection();

        $columns = array_keys($connection->describeTable($sourceTableName));

        $select = $connection->select()
            ->from($sourceTableName, $columns);

        $query = $select->insertFromSelect($destinationTableName, $columns);

        $connection->query($query);
    }
}
