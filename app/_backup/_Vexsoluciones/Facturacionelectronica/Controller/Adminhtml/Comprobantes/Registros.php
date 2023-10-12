<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 08/11/2018
 * Time: 08:20 PM
 */

namespace Vexsoluciones\Facturacionelectronica\Controller\Adminhtml\comprobantes;

class Registros extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Registro Docs')));

        return $resultPage;
    }
}