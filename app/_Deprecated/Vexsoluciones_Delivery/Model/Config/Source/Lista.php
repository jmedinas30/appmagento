<?php
namespace Vexsoluciones\Delivery\Model\Config\Source;

class Lista implements \Magento\Framework\Option\ArrayInterface
{
 public function toOptionArray()
 {
 	$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
	$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
	$connection = $resource->getConnection();
	$tableName = $resource->getTableName('inventory_source');

	$sql = "Select * FROM " . $tableName;
	$result = $connection->fetchAll($sql);

	$lista = array();
	$lista[] = array('value' => '0', 'label' => "Todos los almacenes");
	foreach ($result as $key) {
		$lista[] = array('value' => $key['source_code'], 'label' => $key['name']);
	}

  return $lista;
 }
}