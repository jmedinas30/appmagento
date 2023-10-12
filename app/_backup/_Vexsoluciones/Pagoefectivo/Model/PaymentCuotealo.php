<?php

namespace Vexsoluciones\Pagoefectivo\Model;

use Magento\Payment\Model\Method\AbstractMethod;

class PaymentCuotealo extends AbstractMethod
{
    const CODE = 'cuotealo_pay';

    protected $_code = self::CODE;
    protected $_isGateway = true;
    protected $_canCapture = true;
    protected $_canCapturePartial = true;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canAuthorize = true;

    protected $_minAmount;
    protected $_maxAmount;
    protected $_privateKey;
    protected $_publicKey;
    protected $_supportedCurrencyCodes = array('USD', 'PEN');
    protected $_debugReplacePrivateDataKeys = ['number', 'exp_month', 'exp_year', 'cvc', 'source_id'];
    /**
     * @var \Magento\Sales\Model\Service\InvoiceService
     */
    private $invoiceService;
    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\InvoiceSender
     */
    private $invoiceSender;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\App\Filesystem\DirectoryList $dir,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
        array $data = array()
    ) {

        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            null,
            null,
            $data
        );


        $this->_minAmount = $this->getConfigData('min_order_total');
        $this->_maxAmount = $this->getConfigData('max_order_total');
        $this->_privateKey = $encryptor->decrypt($this->getConfigData('private_key'));
        $this->_publicKey = $this->getConfigData('public_key');
        $this->invoiceService = $invoiceService;
        $this->invoiceSender = $invoiceSender;
    }

    /**
     *
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Validator\Exception
     */
    public function capture(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {


        return parent::capture($payment, $amount);
    }


    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {

        $order = $payment->getOrder();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        $hd = $objectManager->get('Vexsoluciones\Pagoefectivo\Helper\Data');

        $tipo_documento = $payment->getAdditionalInformation('tipo_documento');
        $documento = $payment->getAdditionalInformation('documento');

        $data = $hd->get_generate_cip( $order,$tipo_documento,$documento,"cuotealo" );
                    
        if ( $data["status"] ) {
            //URL
            $data = $data["msg"];
            $cipUrl = '';
            //Si existe nuevo CIP
            if ( isset( $data->cipUrl ) ) {                                                       
                //Registrar nueva información
                $cipUrl = $data->cipUrl; 
                

            //Sino que ya existe un CIP registrado para el pedido
            } else if ( isset( $data->cipUrlSaved ) ) { 
                $cipUrl = $data->cipUrlSaved;
            }                    

            //Si existe url de CIP definida
            if ( $cipUrl ) {
                //Definir contenido
                $payment->setAdditionalInformation('cip_url', $cipUrl);
                $payment->setAdditionalInformation('respuesta_cip', json_encode($data));
                
                
            }else{
                throw new \Magento\Framework\Validator\Exception(__('Ocurrió un error durante la generación de su código CIP.'));
            }

        } else {
            //Definir contenido
            throw new \Magento\Framework\Validator\Exception(__($data["msg"]));
        }

        return parent::authorize($payment, $amount);
    }

    /**
     *
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     * @throws \Magento\Framework\Validator\Exception
     */
    public function refund(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        
        return $this;
    }

    /**
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        
        if($quote && $quote->getData("quote_currency_code")!="USD" && $quote->getData("quote_currency_code")!="PEN"){
            return false;
        }
        return parent::isAvailable($quote);
    }

    /**
     *
     * @param string $currencyCode
     * @return bool
     */
    public function canUseForCurrency($currencyCode)
    {
        
        return true;
    }
}