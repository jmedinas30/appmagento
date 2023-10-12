<?php

namespace Vexsoluciones\Delivery\Controller\Adminhtml\Sector;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

/**
 * MassBlogDelete Class
 */
class DeshabilitarMassaction extends \Magento\Backend\App\Action
{

    
    protected $sectorModel;

    /**
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $_massModel;

    /** @var \Webkul\MpBlog\Helper\Data
     */
    protected $_helperData;


    public function __construct(
        \Vexsoluciones\Delivery\Model\SectorFactory $sectorModel,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Ui\Component\MassAction\Filter $massModel,
        Action\Context $context
    ) {
        $this->sectorModel = $sectorModel;
        $this->_massModel = $massModel;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        
        $Collection = $this->sectorModel->create()->getCollection();
        $model = $this->_massModel;
        $collection = $model->getCollection($Collection);
        try {
           foreach ($collection as $profesional) {

                $profesional->setStatus(0);
                $profesional->save();
            
            }
            $this->messageManager->addSuccess(__('Se deshabilitaron de forma exitosa'));
            
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('vexsoluciones_delivery/sector/index');
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Vexsoluciones_Delivery::sector');
    }
}
