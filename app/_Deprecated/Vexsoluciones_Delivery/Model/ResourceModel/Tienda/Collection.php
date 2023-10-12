<?php
/**
 * Copyright © 2015 Vexsoluciones. All rights reserved.
 */

namespace Vexsoluciones\Delivery\Model\ResourceModel\Tienda;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Vexsoluciones\Delivery\Model\Tienda', 'Vexsoluciones\Delivery\Model\ResourceModel\Tienda');
    }
}
