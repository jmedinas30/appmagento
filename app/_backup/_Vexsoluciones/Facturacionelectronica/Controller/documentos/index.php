<?php
namespace Vexsoluciones\Facturacionelectronica\Controller\documentos;


class Index extends \Magento\Framework\App\Action\Action
{

    protected $coreRegistry = null;

    protected $session = null;

    protected $resultPageFactory;

    protected $configDAO;
    protected $comprobanteDAO;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $session,
        \Vexsoluciones\Facturacionelectronica\Vexfecore\DAO\configDAO $configDAO,
        \Vexsoluciones\Facturacionelectronica\Vexfecore\DAO\comprobanteDAO $comprobanteDAO
    )
    {
        $this->coreRegistry = $registry;
        $this->session = $session;
        $this->resultPageFactory = $resultPageFactory;

        $this->configDAO = $configDAO;

        $this->comprobanteDAO = $comprobanteDAO;

        return parent::__construct($context);
    }


    public function execute()
    {

        $comprobante_id = $this->session->getData('comprobante_id');

        list($comprobante_info) = $this->comprobanteDAO->get(['id' => $comprobante_id]);

        $configInfo = $this->configDAO->get();

        $nombrePDF = $configInfo['ruc'].'-'.$comprobante_info['serie'].'-'.$comprobante_info['numero'].'.pdf';

        $filePDF = _VEXFE_DIR_DOCUMENTOS_.DIRECTORY_SEPARATOR.$nombrePDF;

        if( file_exists($filePDF) )
        {

            header("Content-type:application/pdf");
            header("Content-Disposition:attachment;filename='".$nombrePDF."'");

            readfile($filePDF);

        }

        die();

        //return $this->resultPageFactory->create();
    }


}
