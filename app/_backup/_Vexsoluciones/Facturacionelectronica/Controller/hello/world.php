<?php
  namespace Vexsoluciones\Facturacionelectronica\Controller\Hello;
  use Peru\Http\ContextClient;
  use Peru\Sunat\{HtmlParser, Ruc, RucParser};

  class World extends \Magento\Framework\App\Action\Action
  {
    public function __construct(
        \Magento\Framework\App\Action\Context $context)
    {
        return parent::__construct($context);
    }

    public function execute()
    {

        echo 'Hello World';

        $ruc = '20100070970';

        $cs = new Ruc(new ContextClient(), new RucParser(new HtmlParser()));

        $company = $cs->get($ruc);
        if (!$company) {
            echo 'Not found';
            exit();
        }

        echo json_encode($company);

        /*$dni = '46658592';

        $cs = new Dni(new ContextClient(), new DniParser());

        $person = $cs->get($dni);
        if (!$person) {
            echo 'Not found';
            exit();
        }

        echo json_encode($person);*/

        exit;
    }
  }
