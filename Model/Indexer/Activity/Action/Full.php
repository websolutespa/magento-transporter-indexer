<?php
/*
 * Copyright © Websolute spa. All rights reserved.
 * See LICENSE and/or COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Websolute\TransporterIndexer\Model\Indexer\Activity\Action;

use Exception;
use Magento\Framework\Exception\LocalizedException;
use Websolute\TransporterIndexer\Model\ResourceModel\Indexer\Activity\DefaultIndexerActivity;

class Full
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
     * @return void
     * @throws Exception
     */
    public function execute(): void
    {
        try {
            $this->defaultActivity->reindexAll();
        } catch (Exception $e) {
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }
}
