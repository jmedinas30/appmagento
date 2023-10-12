<?PHP

namespace Vexsoluciones\Facturacionelectronica\Vexfecore;

class serviceFeAPI{

    private $url = 'http://api.nubefactura.com/';

    private $comprobanteDAO = null;
    private $comprobanteDetalleDAO = null;
    private $comprobanteFacturaDAO = null;
    private $comprobanteBoletaDAO = null;
    private $configDAO = null;
    private $logDAO = null;
    private $helperConfig;
    
    /*
      Este metodo recupera los datos del comprobante,
      Llama al API para enviar los datos
      Recibe los datos, genera el XML y el PDF
    */

    public function __construct(\Vexsoluciones\Facturacionelectronica\Helper\Dataconfig $helperConfig,
                                DAO\configDAO $configDAO,
                                DAO\comprobanteDAO $comprobanteDAO,
                                DAO\comprobanteFacturaDAO $comprobanteFacturaDAO,
                                DAO\comprobanteBoletaDAO $comprobanteBoletaDAO,
                                DAO\comprobanteDetalleDAO $comprobanteDetalleDAO,
                                DAO\logDAO $logDAO ){

         $this->helperConfig = $helperConfig;
         $this->comprobanteDAO = $comprobanteDAO;
         $this->comprobanteDetalleDAO = $comprobanteDetalleDAO;
         $this->comprobanteFacturaDAO = $comprobanteFacturaDAO;
         $this->comprobanteBoletaDAO = $comprobanteBoletaDAO;
         $this->configDAO = $configDAO;
         $this->logDAO = $logDAO;

    }
 

    public function enviar_comprobante( $comprobante_id, $tipo_comprobante ){
  
        $result = false;

        if($tipo_comprobante == constants::$TIPOCOMPROBANTE_FACTURA ){

           $objComprobante = $this->comprobanteFacturaDAO;
        }
        else
        {

           $objComprobante = $this->comprobanteBoletaDAO;

        }


        list($infoComprobante) = $objComprobante->get(array('id' => $comprobante_id ));


        $detalleComprobante = $this->comprobanteDetalleDAO->get(array('comprobante_id' => $comprobante_id ));

        //conexion
        //$conn = $this->helperConfig->getSqlserver();
        /*$serverName = "http://azaecommerce.database.windows.net/";
        $connectionOptions = array(
            "Database" => "AZAECOMMERCE",
            "Uid" => "magento",
            "PWD" => "azaleia.2018"
        );
        $conn = sqlsrv_connect($serverName, $connectionOptions);*/

        $itemsObj = array();
        $itemID = 1;

        foreach ($detalleComprobante as $reg) {
 
             $item =  array(
                "unidad_de_medida"          => "NIU",
                "codigo"                    => str_pad($itemID, 3, "0", STR_PAD_LEFT),
                "descripcion"               => $reg['detalle'],
                "cantidad"                  => $reg['cantidad'],
                "valor_unitario"            => $reg['precio_unitario'],
                "precio_unitario"           => $reg['precio_referencial'],
                "descuento"                 => "",
                "subtotal"                  => $reg['subtotal'],
                "tipo_de_igv"               => "1",
                "igv"                       => $reg['impuesto'],
                "total"                     => ($reg['impuesto'] + $reg['subtotal']),
                "anticipo_regularizacion"   => "false",
                "anticipo_documento_serie"  => "",
                "anticipo_documento_numero" => ""
            );
 
            array_push($itemsObj, $item);

            $itemID++;




        }


        $data = array(
            "operacion"       => "generar_comprobante",
            "tipo_de_comprobante"               => $infoComprobante['tipo_de_comprobante'],
            "serie"                             => $infoComprobante['serie'],
            "numero"        => $infoComprobante['numero'],
            "sunat_transaction"     => "1",
            "cliente_tipo_de_documento"   => $infoComprobante['cliente_tipo_de_documento'],
            "cliente_numero_de_documento" => $infoComprobante['cliente_numero_de_documento'],
            "cliente_denominacion"              => $infoComprobante['cliente_denominacion'],
            "cliente_direccion"                 => $infoComprobante['cliente_direccion'],
            "cliente_email"                     => "",
            "cliente_email_1"                   => "",
            "cliente_email_2"                   => "",
            "fecha_de_emision"                  => date('d-m-Y'), //$infoComprobante['fecha_de_emision'],
            "fecha_de_vencimiento"              => "",
            "moneda"                            => "1",
            "tipo_de_cambio"                    => "",
            "porcentaje_de_igv"                 => $infoComprobante['porcentaje_de_igv'],
            "descuento_global"                  => "",
            "descuento_global"                  => "",
            "total_descuento"                   => "",
            "total_anticipo"                    => "",
            "total_gravada"                     => $infoComprobante['total_gravada'],
            "total_inafecta"                    => "",
            "total_exonerada"                   => "",
            "total_igv"                         =>  $infoComprobante['total_igv'],
            "total_gratuita"                    => "",
            "total_otros_cargos"                => "",
            "total"                             => $infoComprobante['total'],
            "percepcion_tipo"                   => "",
            "percepcion_base_imponible"         => "",
            "total_percepcion"                  => "",
            "total_incluido_percepcion"         => "",
            "detraccion"                        => "false",
            "observaciones"                     => "",
            "documento_que_se_modifica_tipo"    => "",
            "documento_que_se_modifica_serie"   => "",
            "documento_que_se_modifica_numero"  => "",
            "tipo_de_nota_de_credito"           => "",
            "tipo_de_nota_de_debito"            => "",
            "enviar_automaticamente_a_la_sunat" => "true",
            "enviar_automaticamente_al_cliente" => "false",
            "codigo_unico"                      => "",
            "condiciones_de_pago"               => "",
            "medio_de_pago"                     => "",
            "placa_vehiculo"                    => "",
            "orden_compra_servicio"             => "",
            "tabla_personalizada_codigo"        => "",
            "formato_de_pdf"                    => "",
            "items" =>  $itemsObj
        );


        $data_json = json_encode($data);
        //print_r($data_json);
        //die();
        // RUTA para enviar documentos
        $ruta =  $this->helperConfig->getUrlEnvio();
 
        //TOKEN para enviar documentos
        $token = $this->helperConfig->getToken();


        try{
              
          //Invocamos el servicio de NUBEFACT
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $ruta);
          curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Token token="'.$token.'"',
            'Content-Type: application/json',
            )
          );

          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $responseTxt  = curl_exec($ch);
          curl_close($ch);
 
          $response = json_decode($responseTxt, true);
 

          $dataResponse = [
              'enlace_del_pdf' => $response['enlace_del_pdf'],
              'enlace_del_xml' => $response['enlace_del_xml'],
              'aceptada_por_sunat' => $response['aceptada_por_sunat'],
              'key' => $response['invoice']['key']
          ];

          $this->comprobanteDAO->actualizar_respuesta_data(['data' => json_encode($dataResponse),
                                                            'comprobante_id' => $comprobante_id]);

           $result = true;
          // Al comprobante se le coloca el ID de la factura (invoice)


        } catch (Exception $e) {


        }

 
     

        return $result;

    }

    public function enviar_comprobante_vexfecore( $comprobante_id, $tipo_comprobante ){



        $result = false;

        if($tipo_comprobante == constants::$TIPOCOMPROBANTE_FACTURA ){

           $objComprobante = $this->comprobanteFacturaDAO;
        }
        else
        {

           $objComprobante = $this->comprobanteBoletaDAO;

        }


        list($infoComprobante) = $objComprobante->get(array('id' => $comprobante_id ));


        $detalleComprobante = $this->comprobanteDetalleDAO->get(array('comprobante_id' => $comprobante_id ));

        $objConfigInfo = $this->configDAO->get();


        $configParametros = $this->configDAO->getConfigParametros();


        $certificado_name = $objConfigInfo['certificado'];

        $token = $objConfigInfo['keyactivacion'];
        $clavecertificado = $objConfigInfo['clavecertificado'];
        $usuariosol = $objConfigInfo['usuariosol'];
        $clavesol = $objConfigInfo['clavesol'];


        $itemsObj = array();
        $itemID = 1;

        foreach ($detalleComprobante as $reg) {

            $igv = 0;

            $item = array(
                "Id" =>  $itemID,
                "Cantidad" =>  $reg['cantidad'],
                "UnidadMedida" =>  'NIU', //$reg['unidad_medida'],
                "CodigoItem" =>   $reg['product_id'],
                "Descripcion" =>  $reg['detalle'],
                "PrecioUnitario" =>  $reg['precio_unitario'],
                "PrecioReferencial" =>  $reg['precio_referencial'],
                "TipoPrecio" =>  "01",  /* Catalogo 16 | 01 precio unitario, 02 valor referencial */
                "TipoImpuesto" =>  "10", /*Catalogo 05 | 1000 impuesto general a las ventas,  */
                "Impuesto" =>  $reg['impuesto'],
                "ImpuestoSelectivo" =>  0,
                "OtroImpuesto" =>  0,
                "Descuento" =>  0,
                "Totalventa" =>  $reg['subtotal'],
                "Suma" =>  ( $reg['subtotal'] + $reg['impuesto'] )
            );

            array_push($itemsObj, $item);

            $itemID++;
        }


        $documento = array(
          "Serie" => $infoComprobante['serie'],
          "Correlativo" => $infoComprobante['numero'],
          "TipoDocumento" => str_pad( $infoComprobante['tipo_de_comprobante'], 2, "0", STR_PAD_LEFT),
          "FechaEmision" => $infoComprobante['fecha_de_emision'],
          "Moneda" => ( trim($infoComprobante['moneda']) == '' ? $configParametros['moneda'] : $infoComprobante['moneda'] ),
          "TipoOperacion" => "01", /* catalogo 17, venta interna 01 */
          "Gravadas" => number_format($infoComprobante['total_gravada'],2),
          "Gratuitas" => 0,
          "Inafectas" => 0,
          "Exoneradas" => 0,
          "DescuentoGlobal" => 0,
          "TotalVenta" => $infoComprobante['total'],
          "TotalIgv" => $infoComprobante['total_igv'],
          "TotalIsc" => 0,
          "TotalOtrosTributos" => 0,
          "PlacaVehiculo" => "",
          "MontoPercepcion" => 0,
          "MontoDetraccion" => 0,
          "tipodocanticipo" => "",
          "CalculoIgv" =>  ( (int) $configParametros['igv'] / 100 ),
          "CalculoIsc" => 0,
          "CalculoDetraccion" => 0,
          "Receptor" => array(
               "NroDocumento" => $infoComprobante['cliente_numero_de_documento'],
               "TipoDocumento" =>$infoComprobante['cliente_tipo_de_documento'],
               "NombreLegal" =>  $infoComprobante['cliente_denominacion']
          ),
          "Items" => $itemsObj
        );


        if(_VEXFE_MODO_TEST_){


          $datosEnvio = array(
              "Token" => 'c7174a-450682-cee8cb-324729-e37782',
              'Certificado'       => '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',
              "ClaveCertificado" => '2017wvaldiviaILO',
              "UsuarioSOL"           => 'MODDATOS',
              "ClaveSOL"             => 'MODDATOS',
              "Documento" => $documento

          );


        }
        else
        {

            $certificadoData = file_get_contents(_VEXFE_DIR_CONFIG_FILES_.DIRECTORY_SEPARATOR.$certificado_name);
            $certificadoBase64 = base64_encode($certificadoData);


            $datosEnvio = array(
                "Token" => $token,
                'Certificado'       => $certificadoBase64,
                "ClaveCertificado" => $clavecertificado,
                "UsuarioSOL"           => $usuariosol,
                "ClaveSOL"             => $clavesol,
                "Documento" => $documento

            );


        }


        // Encapsular aqui la forma de hacer el envio

        $log_descripcion_operacion = 'Envío de datos para su proesamiento al servidor';

        $txt_data_envio = '';
        $txt_data_respuesta = '';
        $mensaje_path = '';

        try {

            $txtJsonDatosEnvio =  json_encode($datosEnvio);

            $txtResponse = callApi::getURL('POST', $this->url.'GenerarComprobante.php', $txtJsonDatosEnvio );
            $txt_data_respuesta = $txtResponse;
            //var_dump('Txt respuesta api', $txt_data_respuesta);
            $response = json_decode($txtResponse, true);


        } catch (Exception $e) {

            $msm = $e->getMessage();
            $log_descripcion_respuesta = 'Algo no salio bien al preparar los datos para su envío '.$msm;

            $this->logDAO->registrar(array('tipo_objeto' => 'comprobante',
                                          'objeto_id' => $comprobante_id,
                                          'descripcion_operacion' => ($log_descripcion_operacion),
                                          'descripcion_respuesta' => ($log_descripcion_respuesta) ));

            $mensaje_path = 'serviceFeAPI/enviar_comprobante/log_1';

            return $return;

        }



        if($response['Exito'] == true){

            $datosRecibidos = $response['Data'];

            if($datosRecibidos['SunatExito'] == true){

                $result = true;

                $codigo =  $infoComprobante['serie'].'-'.$infoComprobante['numero'];

                $xml_nombre = $objConfigInfo['ruc'].'-'.$codigo.'.xml';

                try{

                    $archivoXML = base64_decode($datosRecibidos['DocumentoXML']);
                    $path_archivo = _VEXFE_DIR_DOCUMENTOS_.DIRECTORY_SEPARATOR.$xml_nombre;

                    file_put_contents($path_archivo, $archivoXML);

                 } catch (Exception $e) {

                      $msm = $e->getMessage();
                      $log_descripcion_respuesta = 'El comprobante fue envíado correctamente pero no se pudo recuperar los datos XML '.$msm;
                      $log_tipo = 'ERROR';
                      $mensaje_path = 'serviceFeAPI/enviar_comprobante/log_2';

                 }


                 $this->comprobanteDAO->actualizar_respuesta_sunat(array('comprobante_id' => $comprobante_id,
                                                                      'sunat_respuesta_envio' => $datosRecibidos['DocumentoHashResumen'] ));

                 //var_dump('Generar comprobante_id: ', $comprobante_id);
                 $this->comprobanteDAO->generar_comprobante_impreso($comprobante_id);


                 $log_descripcion_respuesta = 'El comprobante fue envíado correctamente';


                 $mensaje_path = 'serviceFeAPI/enviar_comprobante/log_3';
                 $log_tipo = 'NOTICIA';

            }
            else
            {

                $log_descripcion_respuesta = 'El comprobante fue envíado y esta pendiente de proceso ';



                $mensaje_path = 'serviceFeAPI/enviar_comprobante/log_4';
                $log_tipo = 'OBSERVACION';
            }


        }
        else
        {

            $log_descripcion_respuesta = 'Los datos fueron enviados pero hubo un problema en el servidor ';
            $log_tipo = 'ERROR';

            if( trim($response['MensajeError']) != ''){

              $log_descripcion_respuesta.= trim($response['MensajeError']);

            }

            $txt_data_envio = $txtJsonDatosEnvio;
            $mensaje_path = 'serviceFeAPI/enviar_comprobante/log_5';

        }


        if(_VEXFE_MODO_TEST_){
          $txt_data_envio = $txtJsonDatosEnvio;
        }

        $this->logDAO->registrar(array('tipo_objeto' => 'comprobante',
                                      'mensaje_path' => $mensaje_path,
                                      'objeto_id' => $comprobante_id,
                                      'descripcion_operacion' => trim($log_descripcion_operacion),
                                      'descripcion_respuesta' => trim($log_descripcion_respuesta),
                                      'data_envio' => $txt_data_envio,
                                      'data_respuesta' => $txt_data_respuesta,
                                      'tipo' => $log_tipo ));




        return $result;

    }


    public function consultar_estado_factura(){

       // Si bien los comprobantes

    }

    public function consultar_estado_resumen(){

    }


    public function enviar_baja(){

    }


    public function enviar_comprobante_nubefact(){
 
      $data = array(
          "operacion"       => "generar_comprobante",
          "tipo_de_comprobante"               => "1",
          "serie"                             => "FFF1",
          "numero"        => "1",
          "sunat_transaction"     => "1",
          "cliente_tipo_de_documento"   => "6",
          "cliente_numero_de_documento" => "20600695771",
          "cliente_denominacion"              => "NUBEFACT SA",
          "cliente_direccion"                 => "CALLE LIBERTAD 116 MIRAFLORES - LIMA - PERU",
          "cliente_email"                     => "",
          "cliente_email_1"                   => "",
          "cliente_email_2"                   => "",
          "fecha_de_emision"                  => date('d-m-Y'),
          "fecha_de_vencimiento"              => "",
          "moneda"                            => "1",
          "tipo_de_cambio"                    => "",
          "porcentaje_de_igv"                 => "18.00",
          "descuento_global"                  => "",
          "descuento_global"                  => "",
          "total_descuento"                   => "",
          "total_anticipo"                    => "",
          "total_gravada"                     => "600",
          "total_inafecta"                    => "",
          "total_exonerada"                   => "",
          "total_igv"                         => "108",
          "total_gratuita"                    => "",
          "total_otros_cargos"                => "",
          "total"                             => "708",
          "percepcion_tipo"                   => "",
          "percepcion_base_imponible"         => "",
          "total_percepcion"                  => "",
          "total_incluido_percepcion"         => "",
          "detraccion"                        => "false",
          "observaciones"                     => "",
          "documento_que_se_modifica_tipo"    => "",
          "documento_que_se_modifica_serie"   => "",
          "documento_que_se_modifica_numero"  => "",
          "tipo_de_nota_de_credito"           => "",
          "tipo_de_nota_de_debito"            => "",
          "enviar_automaticamente_a_la_sunat" => "true",
          "enviar_automaticamente_al_cliente" => "false",
          "codigo_unico"                      => "",
          "condiciones_de_pago"               => "",
          "medio_de_pago"                     => "",
          "placa_vehiculo"                    => "",
          "orden_compra_servicio"             => "",
          "tabla_personalizada_codigo"        => "",
          "formato_de_pdf"                    => "",
          "items" => array( 
                          array(
                              "unidad_de_medida"          => "NIU",
                              "codigo"                    => "001",
                              "descripcion"               => "DETALLE DEL PRODUCTO",
                              "cantidad"                  => "1",
                              "valor_unitario"            => "500",
                              "precio_unitario"           => "590",
                              "descuento"                 => "",
                              "subtotal"                  => "500",
                              "tipo_de_igv"               => "1",
                              "igv"                       => "90",
                              "total"                     => "590",
                              "anticipo_regularizacion"   => "false",
                              "anticipo_documento_serie"  => "",
                              "anticipo_documento_numero" => ""
                          ),
                          array(
              "unidad_de_medida"          => "ZZ",
                              "codigo"                    => "001",
                              "descripcion"               => "DETALLE DEL SERVICIO",
                              "cantidad"                  => "5",
                              "valor_unitario"            => "20",
                              "precio_unitario"           => "23.60",
                              "descuento"                 => "",
                              "subtotal"                  => "100",
                              "tipo_de_igv"               => "1",
                              "igv"                       => "18",
                              "total"                     => "118",
                              "anticipo_regularizacion"   => "false",
                              "anticipo_documento_serie"  => "",
                              "anticipo_documento_numero" => ""

                          )
          )
      );
        
      $data_json = json_encode($data);

      /*
      #########################################################
      #### PASO 3: ENVIAR EL ARCHIVO A NUBEFACT ####
      +++++++++++++++++++++++++++++++++++++++++++++++++++++++
      # SI ESTÁS TRABAJANDO CON ARCHIVO JSON
      # - Debes enviar en el HEADER de tu solicitud la siguiente lo siguiente:
      # Authorization = Token token="8d19d8c7c1f6402687720eab85cd57a54f5a7a3fa163476bbcf381ee2b5e0c69"
      # Content-Type = application/json
      # - Adjuntar en el CUERPO o BODY el archivo JSON o TXT
      # SI ESTÁS TRABAJANDO CON ARCHIVO TXT
      # - Debes enviar en el HEADER de tu solicitud la siguiente lo siguiente:
      # Authorization = Token token="8d19d8c7c1f6402687720eab85cd57a54f5a7a3fa163476bbcf381ee2b5e0c69"
      # Content-Type = text/plain
      # - Adjuntar en el CUERPO o BODY el archivo JSON o TXT
      +++++++++++++++++++++++++++++++++++++++++++++++++++++++
      */

      //Invocamos el servicio de NUBEFACT
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $ruta);
      curl_setopt(
        $ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Token token="'.$token.'"',
        'Content-Type: application/json',
        )
      );
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $respuesta  = curl_exec($ch);
      curl_close($ch);

    }

    //Para Sql Server
    public  function FormatErrors( $errors )
    {
        /* Display errors. */
        echo "Error information: ";
        foreach ( $errors as $error )
        {
            echo "SQLSTATE: ".$error['SQLSTATE']."";
            echo "Code: ".$error['code']."";
            echo "Message: ".$error['message']."";
        }
    }
}
