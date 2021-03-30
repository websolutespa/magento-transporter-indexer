<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

namespace Websolute\TransporterIndexer\Model\Indexer;

use Exception;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;
use Websolute\TransporterIndexer\Model\Indexer\Activity\Action\Full;
use Websolute\TransporterIndexer\Model\Indexer\Activity\Action\Row;
use Websolute\TransporterIndexer\Model\Indexer\Activity\Action\Rows;

class Activity extends DataObject implements IndexerActionInterface, MviewActionInterface
{
    /**
     * @var Full
     */
    private $reindexFull;

    /**
     * @var Row
     */
    private $reindexRow;

    /**
     * @var Rows
     */
    private $reindexRows;

    /**
     * @param Full $reindexFull
     * @param Row $reindexRow
     * @param Rows $reindexRows
     */
    public function __construct(
        Full $reindexFull,
        Row $reindexRow,
        Rows $reindexRows
    ) {
        $this->reindexFull = $reindexFull;
        $this->reindexRow = $reindexRow;
        $this->reindexRows = $reindexRows;
    }

    /**
     * Execute materialization on ids entities
     *
     * @param int[] $ids
     * @return void
     * @throws InputException
     * @throws LocalizedException
     */
    public function execute($ids)
    {
        $this->reindexRows->execute($ids);
    }

    /**
     * Execute full indexation
     *
     * @return void
     * @throws Exception
     */
    public function executeFull()
    {
        $this->reindexFull->execute();
    }

    /**
     * Execute partial indexation by ID list
     *
     * @param int[] $ids
     * @return void
     * @throws InputException
     * @throws LocalizedException
     */
    public function executeList(array $ids)
    {
        $this->reindexRows->execute($ids);
    }

    /**
     * Execute partial indexation by ID
     *
     * @param int $id
     * @return void
     * @throws InputException
     * @throws LocalizedException
     */
    public function executeRow($id)
    {
        $this->reindexRow->execute($id);
    }
}
