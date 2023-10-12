define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote'
], function ($, ko, Component, quote) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Vexsoluciones_Delivery/checkout/shipping/carrier_custom'
        },
        metodoenvio : ko.observable(null),
        horariosapi : ko.observable({}),
        
        initObservable: function () {

            var self = this;

            this.selectedMethod = ko.computed(function() {
                var method = quote.shippingMethod();
                var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;

                if(this.metodoenvio()!=selectedMethod){
                    this.metodoenvio(selectedMethod);
                    if(selectedMethod!=null){
                        if(selectedMethod.indexOf('delivery_programado')==0){
                            let id = selectedMethod.replace("delivery_programado", "");

                            let addressaux = quote.shippingAddress();
                            let region = addressaux.regionId;
                            var diasaplicablesaux2 = "";

                            if(window.limaid==region){
                                diasaplicablesaux2 = window.diasvexlima;
                            }else{
                                diasaplicablesaux2 = window.diasvexprovincia;
                            }

                            var htmlaux2 = "";
                            $.each(diasaplicablesaux2, function(index, el) {
                                htmlaux2 += '<option class="optiondia-'+el.dia_semana+'" data-id="'+el.dia_semana+'" value="'+el.anio+"-"+el.mes+"-"+el.dia+'">'+el.nombredia+" "+el.dia+" de "+el.nombremes+" del "+el.anio+'</option>';
                            });
                            $("#select-dia-delivery").html(htmlaux2);


                            $.ajax({
                                url: window.baseurlvex+'rest/V1/getDeliveryProgramado?id='+id,
                                data: JSON.stringify({}),
                                showLoader: true,
                                type: 'GET',
                                dataType: 'json',
                                context: this, 
                                async : false,
                                beforeSend: function(request) {
                                    request.setRequestHeader('Content-Type', 'application/json');
                                },
                                success: function(response){
                                    
                                    if($("#select-dia-delivery").length == 0) {

                                        var interval3 = setInterval(function () {
                    
                                            if($("#select-dia-delivery").length == 0) {

                                            }else{
                                                clearInterval(interval3);

                                                response = response[0];
                                                $("#select-dia-delivery option").attr('disabled','disabled');
                                                self.horariosapi(response);

                                                $.each( response, function( key, value ) {
                                                    let idapi = parseInt(value.dia);
                                                    let inicioapi = parseInt(value.inicio);
                                                    let finapi = parseInt(value.fin);

                                                    if(inicioapi<finapi){
                                                        $("#select-dia-delivery option.optiondia-"+idapi).removeAttr('disabled');
                                                    }
                                                });

                                                var valor = $('#select-dia-delivery option:not([disabled]):first').val();
                                                $('#select-dia-delivery').val(valor);
                                                $('#select-dia-delivery').change();
                                            }

                                        },1500);

                                    }else{

                                        response = response[0];
                                        $("#select-dia-delivery option").attr('disabled','disabled');
                                        self.horariosapi(response);

                                        $.each( response, function( key, value ) {
                                            let idapi = parseInt(value.dia);
                                            let inicioapi = parseInt(value.inicio);
                                            let finapi = parseInt(value.fin);

                                            if(inicioapi<finapi){
                                                $("#select-dia-delivery option.optiondia-"+idapi).removeAttr('disabled');
                                            }
                                        });

                                        var valor = $('#select-dia-delivery option:not([disabled]):first').val();
                                        $('#select-dia-delivery').val(valor);
                                        $('#select-dia-delivery').change();

                                    }
                                    

                                    
                                },

                                complete: function () { 
                                     
                                }
                            });

                        }
                    }

                }
               
                return selectedMethod;
            }, this);



            var interval = setInterval(function () {
                    
                if($("#select-dia-delivery").length == 0) {

                }else{
                    clearInterval(interval);
                    var diasaplicables = window.diasvex;
                    var html = "";
                    $.each(diasaplicables, function(index, el) {
                        html += '<option class="optiondia-'+el.dia_semana+'" data-id="'+el.dia_semana+'" value="'+el.anio+"-"+el.mes+"-"+el.dia+'">'+el.nombredia+" "+el.dia+" de "+el.nombremes+" del "+el.anio+'</option>';
                    });
                    $("#select-dia-delivery").html(html);

                    $(document).on("change","#select-dia-delivery",function(){
                        var valorselect = $(this).children('option:selected').data('id');
                        let ha = self.horariosapi();
                        $.each(ha, function(index, el) {
                            
                            if(el.dia==valorselect){
                                var htmlselect = "";

                                for (var i = parseInt(el.inicio); i < parseInt(el.fin); i++) {
                                    let ds = ((i+1)==24)?0:(i+1);

                                    let inq = (i<10)?"0"+i.toString():i;
                                    let fiq = (ds<10)?"0"+ds.toString():ds;

                                    htmlselect += "<option value='"+i+"'>"+inq+":00 - "+fiq+":00</option>";
                                }
                                $("#select-hora-delivery").html(htmlselect);
                                //return false;
                            }
                        });
                    });

                }

            },1500);


            
            
            

            return this;
        },
    });
});