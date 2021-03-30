<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterIndexer\Model\Indexer\Activity\Action;

use Exception;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Websolute\TransporterIndexer\Model\ResourceModel\Indexer\Activity\DefaultIndexerActivity;

class Rows
{
    /**
     * @var DefaultIndexerActivity
     */
    private $defaultActivity;

    /**
     * @param DefaultIndexerActivity $defaultActivity
     */
    public function __construct(
        DefaultIndexerActivity $defaultActivity
    ) {
        $this->defaultActivity = $defaultActivity;
    }


    /**
     * @param int[] $ids
     * @throws InputException
     * @throws LocalizedException
     */
    public function execute(array $ids)
    {
        if (empty($ids)) {
            throw new InputException(__('Bad value was supplied.'));
        }
        try {
            $this->defaultActivity->reindexEntity($ids);
        } catch (Exception $e) {
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }
}
