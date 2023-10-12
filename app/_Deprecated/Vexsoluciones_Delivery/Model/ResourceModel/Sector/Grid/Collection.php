<?php
/**
 * Copyright © 2015 Vexsoluciones. All rights reserved.
 */

namespace Vexsoluciones\Delivery\Model\ResourceModel\Sector\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class Collection extends SearchResult
{
	protected $_idFieldName = 'id';

	public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'vexsoluciones_reglas_sector',
        $resourceModel = 'Vexsoluciones\Delivery\Model\ResourceModel\Sector',
        $identifierName = null,
        $connectionName = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel, $identifierName, $connectionName);
    }

    

    protected function _initSelect()
    {
        //parent::_initSelect();

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


        
        parent::_initSelect();

        $this->addFilterToMap("nombre_region", "region.default_name");
        $this->addFilterToMap("nombre_provincia", "provincia.nombre_provincia");
        $this->addFilterToMap("nombre_distrito", "distrito.nombre_distrito");
        $this->addFilterToMap("id", "main_table.id");
        
    }

}