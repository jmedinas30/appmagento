<?php

namespace Vexsoluciones\Facturacionelectronica\Model\ResourceModel\Grid;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'comprobante_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('Vexsoluciones\Facturacionelectronica\Model\Grid', 'Vexsoluciones\Facturacionelectronica\Model\ResourceModel\Grid');
    }
}
