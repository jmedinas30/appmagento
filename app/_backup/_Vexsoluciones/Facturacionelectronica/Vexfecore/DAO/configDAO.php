<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore\DAO;


class configDAO{

    private $cn = null;

    public $table = 'vexfe_config'; // No colocar _VEXFE_DB_PREFIX_ porque en los metodos CRUD se agrega automaticamente

    public $_fields = array(

        'id',
        'keyactivacion',
        'usuariosol',
        'clavesol',
        'certificado',
        'clavecertificado',
        'ruc',
        'razonsocial',
        'fecha_hora',
        'estado'
    );

    public function __construct( \Vexsoluciones\Facturacionelectronica\Vexfecore\Database $cn){

        //var_dump('Me crearon CONFIG con ', get_class($cn), spl_object_hash($cn) );
        $this->cn = $cn;

    }

    public function registrar($params = array()){


        $sql = "SELECT * FROM "._VEXFE_DB_PREFIX_.$this->table." WHERE estado = 1 ";
        list($results) = $this->cn->query($sql);

        if( array_key_exists('certificado', $params) && trim($params['certificado']) == ''){

            $params['certificado'] = $results['certificado'];

        }

        if( array_key_exists('logo', $params) && trim($params['logo']) == ''){

            $params['logo'] = $results['logo'];

        }



        $sql = "UPDATE "._VEXFE_DB_PREFIX_.$this->table." SET estado = 0 ";
        $this->cn->execute($sql);

        $ok = $this->cn->insert( $this->table, $params );

        return ($ok ? $ok : false);

    }

    public function get(){

        $sql = "SELECT * FROM "._VEXFE_DB_PREFIX_.$this->table." WHERE estado = 1 ";

        $results = $this->cn->query($sql);

        if(sizeof($results) > 0){
            return $results[0];
        }

        return [];

    }

    public function actualizarReferencia($params = array() ){


        if( trim($params['ruc']) != '' && trim($params['razonsocial']) != '' ){

            $sql = "UPDATE "._VEXFE_DB_PREFIX_.$this->table."
                    SET ruc = '".$params['ruc']."', razonsocial = '".$params['razonsocial']."'
                    WHERE estado = 1 ";
            /*
            $this->cn->update($this->table, array('ruc' => $params['ruc'],
                                                           'razonsocial' => $params['razonsocial']),
                                                    'estado = 1' ); */

            return $this->cn->execute($sql);
        }


    }

    public function getConfigParametros(){

        $sql= "SELECT * FROM "._VEXFE_DB_PREFIX_."vexfe_config_parametros";

        $results = $this->cn->query($sql);


        if(is_array($results) == false || sizeof($results) == 0){

            $configParametros =  ['igv' => '',
                                  'moneda' => '',
                                  'unidad_de_medida' => ''];
        }
        else
        {
            $configParametros = $results[0];
        }


        if($configParametros['igv'] == '') $configParametros['igv'] = 18;
        if($configParametros['moneda'] == '') $configParametros['moneda'] = 'PEN';
        if($configParametros['unidad_de_medida'] == '') $configParametros['unidad_de_medida'] = 'UNIDAD';

        return $configParametros;
    }

    public function updateConfigParametros( $params = array() ){

        $sql= "UPDATE "._VEXFE_DB_PREFIX_."vexfe_config_parametros
               SET moneda = '".$params['moneda']."',
                   unidad_de_medida = '".$params['unidad_de_medida']."',
                   igv = '".$params['igv']."'";

        $rs = $this->cn->execute($sql);
        return $rs[0];

    }

}
