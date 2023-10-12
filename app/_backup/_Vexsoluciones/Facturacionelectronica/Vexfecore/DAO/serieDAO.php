<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore\DAO;


class serieDAO{

    private $cn = null;

    public $table = 'vexfe_serie';

    public function __construct( \Vexsoluciones\Facturacionelectronica\Vexfecore\Database $cn){

        //var_dump('Me crearon SERIE con ', get_class($cn), spl_object_hash($cn)  );
        $this->cn = $cn;

    }

    public function siguente_numero_serie_comprobante($params = array()){

        $sql ="SELECT CONCAT( serie_tipo, LPAD(serie_numero,3,0) ) as serie, correlativo
               FROM "._VEXFE_DB_PREFIX_.$this->table."
               WHERE tipo_de_comprobante = '".$params['tipo_comprobante']."' AND estado = 1; ";
        // FOR UPDATE;


        $rows = $this->cn->query($sql);
        return array($rows[0]['serie'],$rows[0]['correlativo']);

    }

    public function aumentar_serie_comprobante( $params = array() ){

        $sql = "UPDATE "._VEXFE_DB_PREFIX_.$this->table."
                SET correlativo = correlativo + 1
                WHERE tipo_de_comprobante = '".$params['tipo_comprobante']."' AND estado = 1 ";

        $rs = $this->cn->execute($sql);
        return $rs;

    }

}
