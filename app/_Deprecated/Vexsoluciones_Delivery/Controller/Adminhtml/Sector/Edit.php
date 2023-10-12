<?php
/**
 * Blog
 * 
 * @author Slava Yurthev
 */
namespace Vexsoluciones\Delivery\Controller\Adminhtml\Sector;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action{
	protected $_coreRegistry = null;
	protected $resultPageFactory;
	public function __construct(
		Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Magento\Framework\Registry $registry
	) {
		$this->resultPageFactory = $resultPageFactory;
		$this->_coreRegistry = $registry;
		parent::__construct($context);
	}
	protected function _isAllowed(){
		return $this->_authorization->isAllowed('Vexsoluciones_Delivery::sector');
	}
	protected function _initAction(){
		$resultPage = $this->resultPageFactory->create();
		$resultPage->setActiveMenu('Vexsoluciones_Delivery::sector')
			->addBreadcrumb(__('Sector'), __('Sector'))
			->addBreadcrumb(__('Editar'), __('Editar'));
		return $resultPage;
	}
	public function execute(){
		$id = $this->getRequest()->getParam('id');
		$model = $this->_objectManager->create('Vexsoluciones\Delivery\Model\Sector');
		if ($id) {
			$model->load($id);
			if (!$model->getId()) {
				$resultRedirect = $this->resultRedirectFactory->create();
				return $resultRedirect->setPath('*/*/');
			}
		}
		$data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}
		$this->_coreRegistry->register('sector', $model);
		$resultPage = $this->_initAction();
		$resultPage->getConfig()->getTitle()->prepend(__('Sector'));

		if ($id) {
			$resultPage->getConfig()->getTitle()->prepend(__('Editar sector'));
		}else{
			$resultPage->getConfig()->getTitle()->prepend(__('Agregar sector'));
		}
		
		return $resultPage;
	}
}