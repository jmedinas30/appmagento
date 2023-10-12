<?PHP
namespace Vexsoluciones\Facturacionelectronica\Model;

class FeMagento{

    // CheckoutFacturacionFieldsRepositoryInterface
    public function __construct(){

    }

    public function getMensaje(){

        $objTest = new \Vexsoluciones\Facturacionelectronica\Vexfecore\testLlamada();

        return 'Hola mundo';
    }

}
