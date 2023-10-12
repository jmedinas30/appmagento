<?php
/**
 * Blog
 * 
 * @author Slava Yurthev
 */
namespace Vexsoluciones\Delivery\Controller\Adminhtml\Sector;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Index extends Action {
	protected $_resultPageFactory;
	protected $_resultPage;
	public function __construct(Context $context, PageFactory $resultPageFactory){
		parent::__construct($context);
		$this->_resultPageFactory = $resultPageFactory;
	}
	public function execute(){
		$this->_setPageData();
		return $this->getResultPage();
	}
	protected function _isAllowed(){
		return $this->_authorization->isAllowed('Vexsoluciones_Delivery::sector');
	}
	public function getResultPage(){
		if (is_null($this->_resultPage)){
			$this->_resultPage = $this->_resultPageFactory->create();
		}
		return $this->_resultPage;
	}
	protected function _setPageData(){
		$resultPage = $this->getResultPage();
		$resultPage->setActiveMenu('Vexsoluciones_Delivery::sector');
		$resultPage->getConfig()->getTitle()->prepend((__('Lista de sectores')));
		$resultPage->addBreadcrumb(__('Sector'), __('Lista'));
		return $this;
	}
}