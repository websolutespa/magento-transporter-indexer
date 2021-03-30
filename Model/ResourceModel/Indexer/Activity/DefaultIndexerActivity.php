<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterIndexer\Model\ResourceModel\Indexer\Activity;

use Exception;
use Magento\Framework\DB\Select;
use Magento\Framework\Indexer\Table\StrategyInterface;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Indexer\Model\ResourceModel\AbstractResource;
use Websolute\TransporterActivity\Model\ResourceModel\ActivityResourceModel;
use Websolute\TransporterIndexer\Model\ResourceModel\GetActivitiesByIds;
use Websolute\TransporterIndexer\Model\ResourceModel\Reindex\ClearTemporaryIndexTable;
use Websolute\TransporterIndexer\Model\ResourceModel\Reindex\SetActivityIndexRow;
use Websolute\TransporterIndexer\Model\ResourceModel\Reindex\SyncActivityIndex;

class DefaultIndexerActivity extends AbstractResource implements IndexerActivityInterface
{
    /** @var string */
    const TABLE_NAME = 'transporter_activity_index';

    /** @var string */
    const ID_FIELD_NAME = 'activity_id';

    /**
     * @var ClearTemporaryIndexTable
     */
    private $clearTemporaryIndexTable;

    /**
     * @var SyncActivityIndex
     */
    private $syncActivityIndex;

    /**
     * @var SetActivityIndexRow
     */
    private $setActivityIndexRow;

    /**
     * @var GetActivitiesByIds
     */
    private $getActivitiesByIds;

    /**
     * @param Context $context
     * @param StrategyInterface $tableStrategy
     * @param ClearTemporaryIndexTable $clearTemporaryIndexTable
     * @param SyncActivityIndex $syncActivityIndex
     * @param SetActivityIndexRow $setActivityIndexRow
     * @param GetActivitiesByIds $getActivitiesByIds
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        StrategyInterface $tableStrategy,
        ClearTemporaryIndexTable $clearTemporaryIndexTable,
        SyncActivityIndex $syncActivityIndex,
        SetActivityIndexRow $setActivityIndexRow,
        GetActivitiesByIds $getActivitiesByIds,
        $connectionName = null
    ) {
        parent::__construct($context, $tableStrategy, $connectionName);
        $this->clearTemporaryIndexTable = $clearTemporaryIndexTable;
        $this->syncActivityIndex = $syncActivityIndex;
        $this->setActivityIndexRow = $setActivityIndexRow;
        $this->getActivitiesByIds = $getActivitiesByIds;
    }

    /**
     * @return IndexerActivityInterface
     * @throws Exception
     */
    public function reindexAll(): IndexerActivityInterface
    {
        $this->tableStrategy->setUseIdxTable(true);
        $this->clearTemporaryIndexTable();

        $this->beginTransaction();
        try {
            $this->prepareIdxTable();
            $this->commit();
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }

        $idxTableName = $this->tableStrategy->getTableName(DefaultIndexerActivity::TABLE_NAME);

        $this->clearTemporaryIndexTable->execute();
        $this->syncActivityIndex->execute($idxTableName);

        return $this;
    }

    /**
     * Prepare data in temporary idx table
     *
     * @param int|array $entityIds the product limitation
     * @return $this
     */
    private function prepareIdxTable($entityIds = null): IndexerActivityInterface
    {
        $connection = $this->getConnection();
        $select = $this->getActivityIndexSelect($entityIds);
        $query = $select->insertFromSelect($this->getIdxTable());
        $connection->query($query);

        return $this;
    }

    /**
     * Get the select object for get activity index by activity ids
     *
     * @param int|array $activityIds
     * @return Select
     */
    protected function getActivityIndexSelect($activityIds = null): Select
    {
        $connection = $this->getConnection();

        $select = $connection->select()->from(
            ['a' => $this->getTable(ActivityResourceModel::TABLE_NAME)],
            ['activity_id', 'status', 'type']
        );

        if ($activityIds !== null) {
            $select->where('a.activity_id IN(?)', $activityIds);
        }

        return $select;
    }

    /**
     * @param array|int $entityIds
     * @return IndexerActivityInterface
     */
    public function reindexEntity($entityIds): IndexerActivityInterface
    {
        $activities = $this->getActivitiesByIds->execute($entityIds);

        foreach ($activities as $activity) {
            $data[] = [
                'activity_id' => $activity['activity_id'],
                'type' => $activity['type'],
                'status' => $activity['status']
            ];
            $this->setActivityIndexRow->execute($data);
        }
        return $this;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::ID_FIELD_NAME);
    }
}
