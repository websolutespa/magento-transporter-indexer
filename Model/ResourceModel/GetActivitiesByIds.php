<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterIndexer\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;
use Websolute\TransporterActivity\Model\ResourceModel\ActivityResourceModel;

class GetActivitiesByIds
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
     * @param array $ids
     * @return array
     */
    public function execute(array $ids): array
    {
        $tableName = $this->resourceConnection->getTableName(ActivityResourceModel::TABLE_NAME);
        $connection = $this->resourceConnection->getConnection();
        $query = $connection->select()
            ->from($tableName, ['type', 'status'])
            ->where(ActivityResourceModel::ID_FIELD_NAME . ' in (?)', $ids);

        return $connection->fetchAll($query);
    }
}
