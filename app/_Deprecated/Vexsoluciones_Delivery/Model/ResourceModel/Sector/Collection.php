<?php
/**
 * Copyright © 2015 Vexsoluciones. All rights reserved.
 */

namespace Vexsoluciones\Delivery\Model\ResourceModel\Sector;

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
        $this->_init('Vexsoluciones\Delivery\Model\Sector', 'Vexsoluciones\Delivery\Model\ResourceModel\Sector');
    }


    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()->joinLeft(
                ['region' => $this->getTable('directory_country_region')],
                'region.region_id = main_table.departamento_id',
                'default_name as nombre_region'
            );

        $this->getSelect()->joinLeft(
                ['provincia' => $this->getTable('vexsoluciones_directorio_provincia')],
                'provincia.id = main_table.provincia_id',
                'nombre_provincia'
            );

        $this->getSelect()->joinLeft(
                ['distrito' => $this->getTable('vexsoluciones_directorio_distrito')],
                'distrito.id = main_table.distrito_id',
                'nombre_distrito'
            );
        $this->addFilterToMap("id", "main_table.id");

        return $this;
    }

}
