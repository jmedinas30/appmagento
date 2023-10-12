<?php

namespace Vexsoluciones\Pagoefectivo\Controller\Notificacion;

use Magento\Sales\Model\Order;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Framework\DB\Transaction;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class Index extends \Magento\Framework\App\Action\Action implements CsrfAwareActionInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $_checkoutSession;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    private $_orderFactory;

    /**
     * @var \Magento\Sales\Model\OrderRepository
     */

    private $_orderRepository;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $_scopeConfig;

    /**
     * @var  \Magento\Framework\Webapi\Soap\ClientFactory
     */
    private $_soapClientFactory;

    /**
     * @var \Magento\Framework\App\Action\Context
     */
    private $_context;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    private $_orderSender;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface
     */

    private $_helper;
    public $quoteManagement;
    public $logger;

    protected $orderRepository;
    protected $invoiceService;
    protected $transaction;
    protected $invoiceSender;
    private $jsonResultFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Webapi\Soap\ClientFactory $soapClientFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Quote\Api\CartManagementInterface $quoteManagement,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,

        OrderRepositoryInterface $orderRepository,
        InvoiceService $invoiceService,
        InvoiceSender $invoiceSender,
        Transaction $transaction,
        JsonFactory $jsonResultFactory

    ) {

        parent::__construct($context);
        $this->_context = $context;
        $this->_soapClientFactory = $soapClientFactory;
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_orderSender = $orderSender;
        
        $this->quoteManagement = $quoteManagement;

        $this->orderRepository = $orderRepository;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
        $this->invoiceSender = $invoiceSender;
        $this->jsonResultFactory = $jsonResultFactory;

    }

    public function execute()
    {
        
        
        $result = $this->jsonResultFactory->create();

        $signature = $this->getRequest()->getHeader('Pe-Signature');
        $responseBody = $this->getRequest()->getContent();



        if (!$signature) {
            return $this->sendResponse($result, 200, ['message' => 'Signature is empty']);
        }

        if (!$responseBody) {
            return $this->sendResponse($result, 200, ['message' => 'Body is empty']);
        }

        try {
            
            
            $info = json_decode($responseBody);             
            //Si se obtuvo un objeto y contiene tipo de evento 
            if ( is_object( $info ) && isset( $info->eventType ) ) {                         
                //Registrar mensaje
                                   
                //Obtener mas información
                $data = $info->data;
                //Si el CIP ha sido pagado
                if ( $info->eventType == 'cip.paid' ) {  

                    $resultado = array("status"=>true, "msg"=>'');

                    $ordernumber = $data->transactionCode;

                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($ordernumber);

                    if($order->getEntityId()){
                        $this->invoice($order);

                        $payment = $order->getPayment();
                        $payment->setAdditionalInformation('respuesta_notificacion', json_encode($info));
                        $order->addStatusHistoryComment('LLego la notificacion');

                        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
                        $hd = $objectManager->get('Vexsoluciones\Pagoefectivo\Helper\Data');
                        
                        if($payment->getMethod()=="pagoefectivo_pay"){ 
                            $order->setStatus($hd->obtener_statusnotificacion("pagoefectivo"));
                        }
                        if($payment->getMethod()=="cuotealo_pay"){ 
                            $order->setStatus($hd->obtener_statusnotificacion("cuotealo"));
                        }

                        $order->save();
                        
                    }
                    
                    
                
                } else {
                    $resultado = array("status"=>false, "msg"=>'No se pagó el CIP');
                }

            } else {
                $resultado = array("status"=>false, "msg"=>'No se obtuvo información de pago');
            }  



        } catch (Exception $e) {

            return $this->sendResponse($result, 400, ['message' => $e->getMessage()]);
        }

        return $this->sendResponse($result, 200, ['message' => 'OK']);



        
    }




    public function invoice($order){

        if ($order->canInvoice()) {
            $invoice = $this->invoiceService->prepareInvoice($order);
            $invoice->register();
            $invoice->save();
            
            $transactionSave = 
                $this->transaction
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());
            $transactionSave->save();
            $this->invoiceSender->send($invoice);
            
            $order->addCommentToStatusHistory(
                __('Notified customer about invoice creation #%1.', $invoice->getId())
            )->setIsCustomerNotified(true)->save();
        }

    }
    
    private function sendResponse(Json $result, int $code, array $data)
    {
        $data['status'] = $code;

        $result->setHttpResponseCode($code);
        $result->setData($data);

        return $result;
    }

    public function createCsrfValidationException(RequestInterface $request): ? InvalidRequestException{
        return null;
    }
    
    public function validateForCsrf(RequestInterface $request): ?bool{
        return true;
    }

}
