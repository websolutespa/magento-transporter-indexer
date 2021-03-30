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

class Row
{
    /**
     * @var Rows
     */
    private $rows;

    /**
     * @param Rows $rows
     */
    public function __construct(
        Rows $rows
    ) {
        $this->rows = $rows;
    }

    /**
     * @param int $id
     * @throws InputException
     * @throws LocalizedException
     */
    public function execute(int $id)
    {
        if (!isset($id) || empty($id)) {
            throw new InputException(
                __('We can\'t rebuild the index for an undefined activity.')
            );
        }
        try {
            $this->rows->execute([$id]);
        } catch (Exception $e) {
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }
}
