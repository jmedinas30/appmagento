<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\Locations\Cron;

use Magento\Framework\Event\ManagerInterface as EventManagerInterface;

class AssignByCondition
{
    /** @var EventManagerInterface */
    protected $eventManager;

    /**
     * @param EventManagerInterface $eventManager
     */
    public function __construct(
        EventManagerInterface $eventManager
    ) {
        $this->eventManager = $eventManager;
    }

    /**
     * Generate event by template type
     *
     * @param int $typeId
     */
    public function assignByCondition()
    {
        $this->eventManager->dispatch(
            'mageworx_locations_assign_by_cron',
            []
        );
    }
}
