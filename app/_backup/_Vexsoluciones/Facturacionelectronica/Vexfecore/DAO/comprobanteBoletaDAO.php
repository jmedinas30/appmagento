<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore\DAO;


class comprobanteBoletaDAO extends comprobanteDAO{

    public $tipo_de_documento = '2';

    public function get($params = array()){

        /*
         // Version anterior
         $sql = "SELECT comprobante.*,
                        COALESCE(envio_sunat_fallidos.total,0) as sunat_envios_fallidos,
                        COALESCE(envio_sunat_conformes.total,0) as sunat_envios_conformes

                 FROM "._VEXFE_DB_PREFIX_."vexfe_comprobantes comprobante
                 LEFT JOIN (
                     SELECT comprobante_id,
                            count(*) as total
                     FROM "._VEXFE_DB_PREFIX_."vexfe_envios_facturas
                     WHERE estado_sunat = ".constants::$FACTURA_ENVIADA_OK."
                     GROUP BY comprobante_id
                     ORDER BY comprobante_id
                 ) as envio_sunat_conformes ON comprobante.comprobante_id = envio_sunat_conformes.comprobante_id

                 LEFT JOIN (
                     SELECT comprobante_id,
                            count(*) as total
                     FROM "._VEXFE_DB_PREFIX_."vexfe_envios_facturas
                     WHERE estado_sunat = ".constants::$FACTURA_ENVIO_FALLIDO."
                     GROUP BY comprobante_id
                     ORDER BY comprobante_id
                 ) as envio_sunat_fallidos ON comprobante.comprobante_id = envio_sunat_fallidos.comprobante_id

                 WHERE TRUE ";
            */

        $sql = "SELECT comprobante.*
                FROM "._VEXFE_DB_PREFIX_."vexfe_comprobantes comprobante
                WHERE TRUE ";


        $sql.=" AND tipo_de_comprobante = ".$this->tipo_de_documento;

        if( array_key_exists('id', $params) && $params['id'] != ''){
            $sql.=" AND comprobante.comprobante_id = ".$params['id'];
        }

        $results = $this->cn->query($sql);

        return $results;

    }


}
