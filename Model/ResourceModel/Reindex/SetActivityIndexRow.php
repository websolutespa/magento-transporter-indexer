<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterIndexer\Model\ResourceModel\Reindex;

use Magento\Framework\App\ResourceConnection;
use Websolute\TransporterIndexer\Model\ResourceModel\Indexer\Activity\DefaultIndexerActivity;

class SetActivityIndexRow
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
     * @param $data
     */
    public function execute(array $data)
    {
        $tableName = $this->resourceConnection
            ->getTableName(DefaultIndexerActivity::TABLE_NAME);

        $connection = $this->resourceConnection->getConnection();

        $connection->insertOnDuplicate(
            $tableName,
            $data,
            [
                'type',
                'status'
            ]
        );
    }
}
