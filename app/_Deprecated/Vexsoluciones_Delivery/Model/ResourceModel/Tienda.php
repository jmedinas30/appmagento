<?php
/**
 * Copyright © 2015 Vexsoluciones. All rights reserved.
 */

namespace Vexsoluciones\Delivery\Model\ResourceModel;

class Tienda extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('vexsoluciones_tiendas', 'id');
    }
}
