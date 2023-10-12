<?php

namespace Vexsoluciones\Delivery\Controller\Adminhtml\Sector;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Save extends Action {
	protected $_resultPageFactory;
	protected $_resultPage;


	public function __construct(
			Context $context, 
			PageFactory $resultPageFactory
		){
		parent::__construct($context);
		$this->_resultPageFactory = $resultPageFactory;
	}
	public function execute(){
		$object_manager = $this->_objectManager;
		$directory = $object_manager->get('\Magento\Framework\Filesystem\DirectoryList');
		$data = $this->getRequest()->getPostValue();

		$resultRedirect = $this->resultRedirectFactory->create();
		$id = $this->getRequest()->getParam('id');
		$model = $this->_objectManager->create('Vexsoluciones\Delivery\Model\Sector');

		$resource = $object_manager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();


		if($id) {
			$model->load($id);
		}

		$listaprecios = (isset($data['precios']))?$data['precios']:array();
		$listahorarios = (isset($data['fechas']))?$data['fechas']:array();
		$listapreciosactuales = (isset($data['listaprecios']))?$data['listaprecios']:array();

		unset($data['precios']);
		unset($data['fechas']);
		unset($data['listaprecios']);

		$model->setData($data);

		try {

			
			$model->save();

			$sector_id = $model->getId();
			$listaprecioseditados = array();
			
			foreach ($listaprecios as $key) {

				$minimo = round(floatval($key['minimo']),2);
				$maximo = round(floatval($key['maximo']),2);
				$precio = round(floatval($key['precio']),2);
				$status = $key['status'];

				if(isset($key['id']) && intval($key['id'])!=0){
					$listaprecioseditados[] = $key['id'];
					$sql = "Update vexsoluciones_reglas_precio set peso_inicio='".$minimo."', peso_fin='".$maximo."', precio='".$precio."' where id='".intval($key['id'])."';";
				}else{
					$sql = "Insert Into vexsoluciones_reglas_precio(peso_inicio, peso_fin, precio, sector_id,status) Values ('".$minimo."','".$maximo."','".$precio."','".$sector_id."','".$status."');";
				}

 				$connection->query($sql);
			
			}

			

			foreach ($listapreciosactuales as $key) {
				if(!in_array($key, $listaprecioseditados)){
					$sqlaux = "DELETE FROM vexsoluciones_reglas_precio where id='".$key."';";
					$connection->query($sqlaux);
					
				}
			}
			
			
			foreach ($listahorarios as $key => $value) {

				$sqlaux = "SELECT * FROM vexsoluciones_reglas_horario where sector_id='".$sector_id."' AND dia='".$key."';";
				$idaux = 0;
				$listaaux = $connection->fetchAll($sqlaux);
				foreach ($listaaux as $key2) {
					$idaux = $key2['id'];
				}

				$inicio = $value['inicio'];
				$fin = $value['fin'];
				$status = $value['status'];

				if($idaux!=0){
					$sql = "Update vexsoluciones_reglas_horario set hora_inicio= '".$inicio."',hora_fin= '".$fin."',status= '".$status."' where id='".$idaux."';";
				}else{
					$sql = "Insert Into vexsoluciones_reglas_horario(dia, hora_inicio, hora_fin, sector_id,status) Values ('".$key."','".$inicio."','".$fin."','".$sector_id."','".$status."');";
				}

 				$connection->query($sql);
			}

			$this->messageManager->addSuccess(__('Guardado.'));
			if ($this->getRequest()->getParam('back')) {
				return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
			}
			$this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
			return $resultRedirect->setPath('*/*/');
		} catch (\Exception $e) {
			$this->messageManager->addException($e, __('Algo salió mal.'.$e->getMessage()));
		}
		$this->_getSession()->setFormData($data);
		return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
	}
	protected function _isAllowed(){
		return $this->_authorization->isAllowed('Vexsoluciones_Delivery::sector');
	}





}