define(
    [
        'ko',
        'jquery',
        'uiComponent',
        'Vexsoluciones_Facturacionelectronica/js/model/datos',
        'mage/storage'
    ],
    function (ko, $, Component, dataFacturacion, storage) {

        'use strict';
        var getUrl = null;
        var enabled = window.checkoutConfig.enabled;
        return Component.extend({

            requiereFactura: dataFacturacion.requiereFactura,
            documento: dataFacturacion.documento,
            denominacion: dataFacturacion.denominacion,
            direccionFiscal: dataFacturacion.direccionFiscal,
            datosIncompletos: dataFacturacion.datosIncompletos,
            datosIncompletos2: dataFacturacion.datosIncompletos2,

            defaults: {
                template: 'Vexsoluciones_Facturacionelectronica/checkout/facturacion-fields'
            },
            enablePlugin: enabled,
            myAjaxCall: function(newValue) {

                let url = window.location.origin + '/rest/V1/validate/ruc';
                let dataRUC = {
                    'data' : {
                        'ruc' : newValue,
                    }
                }

                //fullScreenLoader.startLoader();
                storage.post(
                    url,
                    JSON.stringify(dataRUC),
                    true
                ).done(
                    function (response) {
                        /** Do your code here */
                        console.log(response)
                        alert('Success');
                        //fullScreenLoader.stopLoader();
                    }
                ).fail(
                    function (response) {
                        //fullScreenLoader.stopLoader();
                    }
                );
            },
            initialize: function(){

                let self = this;

                this._super();

                this.requiereFactura.subscribe(function(newValue){

                    // Si requiere factura
                    $('button.checkout').attr('disabled', false);
                    self.datosIncompletos(false);
                    self.datosIncompletos2(false);

                    if(newValue){
                        var expreg = /^[1|2]\d{10}$/;

                        if( dataFacturacion.documento() == '' || dataFacturacion.denominacion() == '' || dataFacturacion.direccionFiscal() == ''  || !expreg.test(dataFacturacion.documento()) ){
                            $('button.checkout').attr('disabled', true);
                            self.datosIncompletos(true);
                        }

                        self.datosIncompletos2(false);

                    }

                    dataFacturacion.saveData();

                });



                console.log("_________________________")
                //this.documento.subscribe(function(newValue){
                $(document).on("focusout","#facturacion-ruc",function(){
                    var newValue = $("#facturacion-ruc").val();
                    // Si requiere factura
                    $('button.checkout').attr('disabled', false);
                    self.datosIncompletos(false);
                    self.datosIncompletos2(false);

                    if(self.requiereFactura()){
                        //var expreg = /^[1|2]\d{10}$/;
                        var expreg = /^\D*\d{11}$/;
                        /*if(self.denominacion() == '' || self.direccionFiscal() == ''  ){
                            $('button.checkout').attr('disabled', true);
                            self.datosIncompletos(true);
                        }*/

                        if (!expreg.test(newValue)){
                            self.datosIncompletos2(true);
                        }else {
                            //this.myAjaxCall(newValue);


                            let url = window.location.origin + '/rest/V1/validate/ruc';
                            let dataRUC = {
                                'data' : {
                                    'ruc' : newValue,
                                }
                            }

                            var body = $('body').loader();
                            body.loader('show');
                            storage.post(
                                url,
                                JSON.stringify(dataRUC),
                                true
                            ).done(
                                function (response) {
                                    /** Do your code here */
                                    //console.log(response)
                                    if (response.message == 'Ruc no existe'){
                                        alert('Ruc no existe')
                                    }else {
                                        self.denominacion(response.razonSocial)
                                        self.direccionFiscal(response.direccion)
                                        if (self.denominacion()){
                                            //console.log(self.direccionFiscal())
                                            dataFacturacion.saveData()
                                        }
                                    }
                                    var body = $('body').loader();
                                    body.loader('hide');


                                }
                            ).fail(
                                function (response) {
                                    var body = $('body').loader();
                                    body.loader('hide');
                                }
                            ).complete(

                            );
                         /*  let url = window.location.origin + '/rest/V1/validate/ruc';
                            let dataRUC = {
                                'data' : {
                                    'ruc' : newValue,
                                }
                            }
                            $.ajax({
                                showLoader: true,
                                url: url,
                                headers: {
                                    'Content-Type':'application/json'
                                },
                                data: JSON.stringify(dataRUC),
                                type: "POST",
                                dataType: 'json'
                            }).done(function (data) {
                                console.log(data);
                            });*/
                        }

                    }


                });

                //this.denominacion.subscribe(function(newValue){
              /*  $(document).on("focusout","#facturacion-razon",function(){
                    var newValue = $("#facturacion-razon").val();

                    $('button.checkout').attr('disabled', false);
                    self.datosIncompletos(false);
                    self.datosIncompletos2(false);

                    if(self.requiereFactura()){
                        //var expreg = /^[1|2]\d{10}$/;
                        var expreg = /^\D*\d{11}$/;
                        if( !expreg.test(self.documento()) || self.documento() == '' || newValue == '' || self.direccionFiscal() == ''  ){

                            $('button.checkout').attr('disabled', true);
                            self.datosIncompletos(true);
                            //self.datosIncompletos2(false);
                        }

                        if (!expreg.test(self.documento())){
                            self.datosIncompletos2(true);
                        }

                    }

                    dataFacturacion.saveData();

                });*/


                //this.direccionFiscal.subscribe(function(newValue){
             /*   $(document).on("focusout","#facturacion-direccion",function(){
                    var newValue = $("#facturacion-direccion").val();

                    $('button.checkout').attr('disabled', false);
                    self.datosIncompletos(false);
                    self.datosIncompletos2(false);
                    //var expreg = /^[1|2]\d{10}$/;
                    var expreg = /^\D*\d{11}$/;
                    if(self.requiereFactura()){

                        if( !expreg.test(self.documento()) || self.documento() == '' || self.denominacion() == '' || newValue == ''  ){

                            $('button.checkout').attr('disabled', true);
                            self.datosIncompletos(true);
                            //self.datosIncompletos2(false);
                        }

                        if (!expreg.test(self.documento())){
                            self.datosIncompletos2(true);
                        }
                    }

                    dataFacturacion.saveData();

                });*/

            },

            validateRuc: function () {
                var newValue = $("#facturacion-ruc").val();
                var expreg = /^\D*\d{11}$/;
                if (expreg.test(newValue)){
                    let url = window.location.origin + '/rest/V1/validate/ruc';
                    let dataRUC = {
                        'data' : {
                            'ruc' : newValue,
                        }
                    }
                    $.ajax({
                        url: url,
                        data: JSON.stringify(dataRUC),
                        showLoader: false,
                        type: 'POST',
                        dataType: 'json',
                        context: this,
                        beforeSend: function(request) {
                            request.setRequestHeader('Content-Type', 'application/json');
                        },
                        success: function(response){
                            console.log('prueba', response)
                        },
                        complete: function () {

                        }
                    });
                }


            }

        });
    }

);