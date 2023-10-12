<?php


namespace Vexsoluciones\Facturacionelectronica\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface;
use Vexsoluciones\Facturacionelectronica\Vexfecore\Vexfecore;
use Vexsoluciones\Facturacionelectronica\Vexfecore\constants;
use Magento\Store\Model\StoreManagerInterface;

class facturacionRegisterToOrder implements ObserverInterface
{
    protected $productRepository;
    protected $layoutFactory;
    protected $fileFactory;
    public $Vexfecore;
    protected $quoteItemFactory;
    protected $_storeManager;
    public function __construct(Vexfecore $Vexfecore,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Quote\Model\Quote\ItemFactory $quoteItemFactory,
        StoreManagerInterface $_storeManager,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory){

        $this->Vexfecore = $Vexfecore;
        $this->fileFactory = $fileFactory;
        $this->quoteItemFactory = $quoteItemFactory;
        $this->_storeManager = $_storeManager;
        $this->productRepository = $productRepository;
    }

    /**
     * Execute observer method.
     *
     * @param Observer $observer Observer
     *
     * @return void
     */

    public function execute(Observer $observer)
    {

        //$order = $observer->getEvent()->getOrder();
        //$invoice = $observer->getEvent()->getInvoice();
      
    }

    
}
