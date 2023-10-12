<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore\DAO;

class comprobanteFacturaDAO extends comprobanteDAO{

    public $tipo_de_comprobante = '1';

    public function get($params = array()){

         /*
         $sql = "SELECT comprobante.*,
                        COALESCE(envio_sunat_fallidos.total,0) as envios_fallidos,
                        COALESCE(envio_sunat_conformes.total,0) as envios_conformes

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


        $sql.=" AND tipo_de_comprobante = ".$this->tipo_de_comprobante;


        if( array_key_exists('id', $params) && trim($params['id']) != ''){

            $sql.=" AND comprobante.comprobante_id = ".$params['id'];
        }



        if( array_key_exists('codigo', $params) && trim($params['codigo']) != ''){

            if( is_numeric(trim($params['codigo'])) ){

                $sql.=" AND (ref_id = ".trim($params['codigo'])." OR numero = ".trim($params['codigo'])." )";
            }
            else{

            }


        }


        if( array_key_exists('documento_del_cliente', $params) && trim($params['documento_del_cliente']) != ''){

            $sql.=" AND cliente_numero_de_documento = '".trim($params['documento_del_cliente'])."'";

        }


        if( array_key_exists('fecha_de_emision', $params) && trim($params['fecha_de_emision']) != ''){

            $sql.=" AND fecha_de_emision = '".trim($params['fecha_de_emision'])."'";

        }

        /*
        if($params['ver'] == 'pendientes')
        {
            $sql.=" AND COALESCE(envio_sunat_conformes.total,0) = 0";
        }
        else if($params['ver'] == 'enviados')
        {

            $sql.=" AND COALESCE(envio_sunat_conformes.total,0) > 0";
        }
        */

        if( array_key_exists('ver', $params) && trim($params['ver']) != ''){

            if($params['ver'] == 'pendientes')
            {
                $sql.=" AND  sunat_respuesta_envio = '' ";
            }
            else if($params['ver'] == 'enviados')
            {

                $sql.=" AND sunat_respuesta_envio != '' ";
            }

        }



        $sql.=" ORDER BY comprobante.fecha_de_emision ASC ";


        $results = $this->cn->query($sql);

        return $results;

    }


    public function getResumenTotales($params = array()){


        $sql = "SELECT 1 as factura,
                       (CASE WHEN COALESCE(envio_sunat_conformes.total,0) > 0 THEN 1 ELSE 0 END) as sunat_envios_conformes

                FROM "._VEXFE_DB_PREFIX_."vexfe_comprobantes comprobante
                LEFT JOIN (
                    SELECT comprobante_id,
                           count(*) as total
                    FROM "._VEXFE_DB_PREFIX_."vexfe_envios_facturas
                    WHERE estado_sunat = ".constants::$FACTURA_ENVIADA_OK."
                    GROUP BY comprobante_id
                    ORDER BY comprobante_id
                ) as envio_sunat_conformes ON comprobante.id = envio_sunat_conformes.comprobante_id

                WHERE tipo_de_comprobante = ".$this->tipo_de_comprobante;


                $sql.=" ORDER BY comprobante.fecha_de_emision ASC ";


        $sqlSum =" SELECT SUM(main.factura) as total_facturas, SUM(main.sunat_envios_conformes) as total_envios_conformes
                  FROM (".$sql.") as main  ";


        $results = $this->cn->query($sql);

        return $results;


    }


}
