<?php
namespace Vexsoluciones\Delivery\Controller\Adminhtml\Sector;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Delete extends Action {
	protected $_resultPageFactory;
	protected $_resultPage;
	public function __construct(Context $context, PageFactory $resultPageFactory){
		parent::__construct($context);
		$this->_resultPageFactory = $resultPageFactory;
	}
	public function execute(){
		$id = $this->getRequest()->getParam('id');
		if($id>0){
			$model = $this->_objectManager->create('Vexsoluciones\Delivery\Model\Sector');
			$model->load($id);
			try {
				$model->delete();

		        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
		        $connection = $resource->getConnection();
		        $sql = "DELETE FROM vexsoluciones_reglas_horario where sector_id='".$id."';";
		        $connection->query($sql);
		        
		        $sql = "DELETE FROM vexsoluciones_reglas_precio where sector_id='".$id."';";
		        $connection->query($sql);
		        

				$directory = $this->_objectManager->get('\Magento\Framework\Filesystem\DirectoryList');
				$this->messageManager->addSuccess(__('Eliminado.'));
			} catch (\Exception $e) {
				$this->messageManager->addSuccess(__('Algo salió mal.'));
			}
		}
		$this->_redirect('*/*');
	}
	protected function _isAllowed(){
		return $this->_authorization->isAllowed('Vexsoluciones_Delivery::sector');
	}
}