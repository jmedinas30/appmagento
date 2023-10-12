<?php
namespace Vexsoluciones\Pagoefectivo\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;


class SaveOrderAfterSubmitObserver implements ObserverInterface
{
    /**
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    protected $_scopeConfig;
    protected $_checkoutSession;
    /**
     * @var \Vexsoluciones\PagoEfectivo\Model\AbstractPagoefCipFactory
     */
    private $factory;
    /**
     * @var \Vexsoluciones\PagoEfectivo\Api\PagoefCipRepositoryInterface
     */
    private $repository;
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $config;
    /**
     * @var PagoEfectivo
     */
    private $pagoEfectivo;
    /**
     * @var \Vexsoluciones\PagoEfectivo\Model\AuthData
     */
    private $authData;
    /**
     * @var Logger
     */
    private $logger;

    public $encryptor;

    /**
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Vexsoluciones\PagoEfectivo\Model\PagoefCipFactory $factory
     * @param \Vexsoluciones\PagoEfectivo\Api\PagoefCipRepositoryInterface $repository
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param PagoEfectivo $pagoEfectivo
     * @param \Vexsoluciones\PagoEfectivo\Model\AuthData $authData
     * @param Logger $logger
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Framework\App\Config\ScopeConfigInterface $config
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->_scopeConfig = $scopeConfig;
        $this->_checkoutSession = $checkoutSession;
        $this->config = $config;
        $this->encryptor = $encryptor;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $payment = $observer->getEvent()->getPayment();
        $order = $payment->getOrder();
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $hd = $objectManager->get('Vexsoluciones\Pagoefectivo\Helper\Data');

        if($payment->getMethod()=="pagoefectivo_pay"){ 
            $order->setStatus($hd->obtener_status("pagoefectivo"));
        }
        if($payment->getMethod()=="cuotealo_pay"){ 
            $order->setStatus($hd->obtener_status("cuotealo"));
        }
        $order->save();
        

        
        return $this;
    }

   
}
