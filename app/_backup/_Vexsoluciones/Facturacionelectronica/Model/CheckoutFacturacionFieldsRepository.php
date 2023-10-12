<?php
/**
 * @package   Vexsoluciones\Facturacionelectronica
 * @author    Slawomir Bodak <slawek.bodak@gmail.com>
 * @copyright © 2017 Slawomir Bodak
 * @license   See LICENSE file for license details.
 */

namespace Vexsoluciones\Facturacionelectronica\Model;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Model\Order;
use Vexsoluciones\Facturacionelectronica\Api\CheckoutFacturacionFieldsRepositoryInterface;
use Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface;
use Peru\Sunat\RucFactory;

class CheckoutFacturacionFieldsRepository implements CheckoutFacturacionFieldsRepositoryInterface
{
    /**
     * Quote repository.
     *
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * ScopeConfigInterface
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * CheckoutFacturacionFieldsInterface
     *
     * @var CheckoutFacturacionFieldsInterface
     */
    protected $customFields;

    /**
     * CustomFieldsRepository constructor.
     *
     * @param CartRepositoryInterface $cartRepository CartRepositoryInterface
     * @param ScopeConfigInterface    $scopeConfig    ScopeConfigInterface
     * @param CheckoutFacturacionFieldsInterface   $customFields   CheckoutFacturacionFieldsInterface
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\QuoteIdMaskFactory $quoteIdMaskFactory,
        CheckoutFacturacionFieldsInterface $facturacionFields
    ) {
        $this->cartRepository = $cartRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->scopeConfig = $scopeConfig;
        $this->customFields = $facturacionFields;
    }
    /**
     * Save checkout custom fields
     *
     * @param string                   $cartId       Cart id
     * @param \Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface $customFields Custom fields
     *
     * @return \Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function saveFacturacionFields(
        string $cartId,
        CheckoutFacturacionFieldsInterface $facturacionFields
    ): CheckoutFacturacionFieldsInterface {
  

        $cart = $this->cartRepository->getActive($cartId);

       

       //  var_dump($cartId, get_class($cart), get_class($facturacionFields),  $facturacionFields->getVexfeDenominacionCliente() );

        if (!$cart->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 is empty', $cartId));
        }

        try {

            // $objApi = new \Vexsoluciones\Facturacionelectronica\Vexfecore\Vexfecore;

            //var_dump($objApi->mensajeTest(), $cartId, $facturacionFields->getVexfeTipoDeComprobante(), CheckoutFacturacionFieldsInterface::TIPO_DE_DOCUMENTO_CLIENTE, $facturacionFields->getVexfeTipoDeDocumentoCliente() );

            /*
                1: Boleta
                2: Factura
            */  

            $tipo_de_comprobante =  CheckoutFacturacionFieldsInterface::COMPROBANTE_BOLETA;
                $tipo_de_documento_cliente = CheckoutFacturacionFieldsInterface::COMPROBANTE_DNI;


            if ($facturacionFields->getRequiereFactura() == '1'){

                $tipo_de_comprobante = CheckoutFacturacionFieldsInterface::COMPROBANTE_FACTURA;
                $tipo_de_documento_cliente = CheckoutFacturacionFieldsInterface::COMPROBANTE_RUC;
            }

            $cart->setData(
                CheckoutFacturacionFieldsInterface::TIPO_DE_COMPROBANTE,
                $tipo_de_comprobante
            ); 

            $cart->setData(
                CheckoutFacturacionFieldsInterface::TIPO_DE_DOCUMENTO_CLIENTE,
                $tipo_de_documento_cliente
            ); 

            $cart->setData(
                CheckoutFacturacionFieldsInterface::NUMERO_DOCUMENTO,
                $facturacionFields->getDocumento()
            ); 

            $cart->setData(
                CheckoutFacturacionFieldsInterface::CLIENTE_DENOMINACION,
                $facturacionFields->getDenominacion()
            );  

            $cart->setData(
                CheckoutFacturacionFieldsInterface::DIRECCION_FISCAL,
                $facturacionFields->getDireccionFiscal()
            );  

            $this->cartRepository->save($cart);

        } catch (\Exception $e) {

            throw new CouldNotSaveException(__('Custom order data could not be saved!'));

        }

        return $facturacionFields;
    }

    /**
     * Save checkout custom fields
     *
     * @param string                   $cartId       Cart id
     * @param \Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface $facturacionFields Custom fields
     *
     * @return \Vexsoluciones\Facturacionelectronica\Api\Data\CheckoutFacturacionFieldsInterface
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function saveFacturacionFieldsGuest(
        string $cartId,
        CheckoutFacturacionFieldsInterface $facturacionFields
    ): CheckoutFacturacionFieldsInterface {
     

       
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($cartId, 'masked_id');
        $cart = $this->cartRepository->getActive($quoteIdMask->getQuoteId());
         

       // var_dump($cartId, get_class($cart), get_class($facturacionFields),  $facturacionFields->getVexfeDenominacionCliente() );

        if (!$cart->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 is empty', $cartId));
        }

        try {

           // $objApi = new \Vexsoluciones\Facturacionelectronica\Vexfecore\Vexfecore;

            //var_dump($objApi->mensajeTest(), $cartId, $facturacionFields->getVexfeTipoDeComprobante(), CheckoutFacturacionFieldsInterface::TIPO_DE_DOCUMENTO_CLIENTE, $facturacionFields->getVexfeTipoDeDocumentoCliente() );
            
           
            $tipo_de_comprobante =  CheckoutFacturacionFieldsInterface::COMPROBANTE_BOLETA;
                $tipo_de_documento_cliente = CheckoutFacturacionFieldsInterface::COMPROBANTE_DNI;


            if ($facturacionFields->getRequiereFactura() == '1'){

                $tipo_de_comprobante = CheckoutFacturacionFieldsInterface::COMPROBANTE_FACTURA;
                $tipo_de_documento_cliente = CheckoutFacturacionFieldsInterface::COMPROBANTE_RUC;
            }

            $cart->setData(
                CheckoutFacturacionFieldsInterface::TIPO_DE_COMPROBANTE,
                $tipo_de_comprobante
            ); 

            $cart->setData(
                CheckoutFacturacionFieldsInterface::TIPO_DE_DOCUMENTO_CLIENTE,
                $tipo_de_documento_cliente
            ); 
            
            $cart->setData(
                CheckoutFacturacionFieldsInterface::NUMERO_DOCUMENTO,
                $facturacionFields->getDocumento()
            ); 

            $cart->setData(
                CheckoutFacturacionFieldsInterface::CLIENTE_DENOMINACION,
                $facturacionFields->getDenominacion()
            );  
            
            $cart->setData(
                CheckoutFacturacionFieldsInterface::DIRECCION_FISCAL,
                $facturacionFields->getDireccionFiscal()
            );  


            $this->cartRepository->save($cart);

        } catch (\Exception $e) {

            throw new CouldNotSaveException(__('Custom order data could not be saved!'));

        }

        return $facturacionFields;
    }


    /**
     * Save store.
     * @api
     * @param mixed $data
     *
     * @return array
     */
    public function getRuc($data){
        header('Content-Type: application/json');
        $ruc = $data['ruc'];


        $factory = new RucFactory();
        $cs = $factory->create();

        $company = $cs->get($ruc);

        if (!$company) {
            $result = [
              'message' => 'Ruc no existe'
            ];
            //$result['message'] ='Ruc no existe';
            echo json_encode($result);
            //return $result;
            exit();
        }

        echo json_encode($company);
        exit;
        //return json_encode($company);
        //return $company;

        //echo json_encode($company);
    }
}
