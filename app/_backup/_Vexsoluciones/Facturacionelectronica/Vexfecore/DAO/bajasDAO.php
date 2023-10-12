<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore\DAO;


class bajasDAO{

    private $cn = null;

    public $table = 'vexfe_bajas'; // No colocar _DB_PREFIX_ porque en los metodos CRUD se agrega automaticamente

    public $_fields = array(

        'id',
        'comprobante_id',
        'serie',
        'numero',
        'fecha_hora',
        'motivo',
        'sunat_respuesta',
        'estado'
    );

    public function __construct( \Vexsoluciones\Facturacionelectronica\Vexfecore\Database $cn){

        $this->cn = $cn;

    }

    public function registrar($params = array()){


        $ok = $this->cn->insert( $this->table, $params );

        return ($ok ? $this->cn->insert_ID() : false);

    }


}
