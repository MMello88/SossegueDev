/*
| ----------------------------------------------------------------------------------
| TABLE OF CONTENT
| ----------------------------------------------------------------------------------
-SETTING
-Sticky Header
-Dropdown Menu Fade
-Animated Entrances
-Accordion
-Filter accordion
-Chars Start
-Сustomization select
-Zoom Images
-ISOTOPE FILTER
-HOME SLIDER
-CAROUSEL PRODUCTS
-PRICE RANGE
-SLIDERS
-Animated WOW
*/



$(document).ready(function() {
    "use strict";
    var siteUrl = $('#siteUrl').val();

    if($('.fancybox').length) {
        $(".fancybox").fancybox();
    }

    if($('.notaRadio label').length) {
        $('.notaRadio label').click(function() {
           $('.notaRadio label').removeClass('fa-star').addClass('fa-star-o');
           $('.notaRadio label').slice(0, parseInt($(this).children().val())).removeClass('fa-star-o').addClass('fa-star');
        });
    }

    if($('#modalCadastro').length) {
        $("#modalCadastro").modal();
    }

    if($('#modalLogin').length) {
        $("#modalLogin").modal();
    }

    if($('#estado').length) {
        $('#estado').change(function() {
            if(this.value !== '') {
                $.ajax({
                    url: siteUrl + 'busca/getCidades?estado=' + this.value,
                    dataType: 'json',
                    success: function(cidades) {
                        var cidadeInput = $('#cidade');

                        cidadeInput.children('option').remove();
                        cidadeInput.prepend($('<option value="" class="option_cad">CIDADE</option>'));

                        for(var i in cidades) {
                            cidadeInput.append($('<option value="' + cidades[i].id + '" class="option_cad">' + cidades[i].nome + '</option>'));
                        }
                    }
                });
            }
        });
    }

    if($('#categoria').length) {
        $('#categoria').change(function() {
            if(this.value !== '') {
                $.ajax({
                    url: siteUrl + 'busca/getSubcategorias?categoria=' + this.value,
                    dataType: 'json',
                    success: function(subcategorias) {
                        var subInput = $('#subcategoria');

                        subInput.children('option').remove();
                        subInput.prepend($('<option value="" class="option_cad">* SUBCATEGORIA</option>'));

                        for(var i in subcategorias) {
                            subInput.append($('<option value="' + subcategorias[i].id + '" class="option_cad">' + subcategorias[i].nome + '</option>'));
                        }
                    }
                });
            }
        });
    }

    if($('#mapa').length) {
		inicializaMapa();
    }

	function inicializaMapa() {
    	var mapa = $('#mapa');
    	var latitude = mapa.data('latitude');
    	var longitude = mapa.data('longitude');

    	if(!latitude || ! longitude) {
    		var location = getLocation(mapa.data('endereco'));
    		latitude = location.latitude;
    		longitude = location.longitude;
    	}

    	var map;
        var profissional = new google.maps.LatLng(latitude,longitude);
        var profissionalNome = mapa.data('profissional');
        var MY_MAPTYPE_ID = 'custom_style';

        var options = {
        	scrollwheel: false,
            zoom: 14,
            center: profissional,
            mapTypeControlOptions: {
		      mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
		    },
            mapTypeId: MY_MAPTYPE_ID
        };

        map = new google.maps.Map(document.getElementById("mapa"), options);

        var styledMapOptions = {name: profissionalNome};
		var customMapType = new google.maps.StyledMapType('',styledMapOptions);
		map.mapTypes.set(MY_MAPTYPE_ID, customMapType);

        var marker = new google.maps.Marker({
            position: profissional,
            title: profissionalNome,
            map: map
        });
    }

    function getLocation(endereco) {
    	var urlMaps = 'http://maps.googleapis.com/maps/api/geocode/json?address=' + endereco + '&sensor=true';
    	var latitude,
    		longitude = '';

		$.ajax({
			url: urlMaps,
			dataType: 'json',
			async: false,
			success: function(data){
	    		if(data.status == 'OK') {
				    latitude = data.results[0].geometry.location.lat;
				    longitude = data.results[0].geometry.location.lng;
	    		}
			}
		});

		return {latitude: latitude, longitude: longitude};
    }

    if($('#senha').length && $('#confSenha').length) {
        var senha = $('#senha');
        var confSenha = $('#confSenha');

        $(confSenha).blur(function() {
           if($(senha).val() !== '' && $(confSenha).val() !== '' && $(senha).val() !== $(confSenha).val()) {
               alert('Senhas não são iguais! Digite novamente!');

               $(senha).val('');
               $(confSenha).val('');

               $(senha).focus();
           }
        });
    }

    if($('#buscaProfissao').length) {
        $.ajax({
            url: siteUrl + 'busca/getProfissoes',
            dataType: 'json',
            success: function(profissoes) {
                $('#buscaProfissao').autocomplete({
                    source: profissoes,
                    minLength: 1
                });
            }
        });

        $.ajax({
            url: siteUrl + 'busca/getCidades',
            dataType: 'json',
            success: function(cidades) {
                $('#buscaCidade').autocomplete({
                    source: cidades,
                    minLength: 1
                });
            }
        });
    }

    if($('.filtro').length) {
        var url = '';

        $('.filtro').change(function() {
            if(this.value) {
                if($(this).data('cidade')) {
                    url = 'cidade=' + $(this).data('cidade') + '&profissao=' + this.value;
                } else {
                    url = 'profissao=' + $(this).data('profissao') + '&cidade=' + this.value;
                }

                location = siteUrl + 'busca/profissionais?' + url;
            }
        });
    }

    $('.money').on('keydown',function(e){
        // tab, esc, enter
        if($.inArray(e.keyCode, [9, 27, 13]) !== -1 ||
            // Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            return;
        }

        e.preventDefault();

        // backspace & del
        if($.inArray(e.keyCode,[8,46]) !== -1){
            $(this).val('');
            return;
        }

        var a = ["a","b","c","d","e","f","g","h","i","`"];
        var n = ["1","2","3","4","5","6","7","8","9","0"];

        var value = $(this).val();
        var clean = value.replace(/\./g,'').replace(/,/g,'').replace(/^0+/, '');

        var charCode = String.fromCharCode(e.keyCode);
        charCode = $.isNumeric(charCode) ? (charCode == 0 ? "`" : a[charCode - 1]) : charCode;
        var p = $.inArray(charCode,a);

        if(p !== -1) {
            value = clean + n[p];

            if(value.length == 2) value = '0' + value;
            if(value.length == 1) value = '00' + value;

            var formatted = '';

            for(var i=0;i<value.length;i++) {
                var sep = '';
//                if(i == 2) sep = ',';
                if(i == 2) sep = '.';
//                if(i > 3 && (i+1) % 3 == 0) sep = '.';
                formatted = value.substring(value.length-1-i,value.length-i) + sep + formatted;
            }

            $(this).val(formatted);
        }

        return;
    });

    if($('#msg').length && $('#msg').val() !== '') {
        $(window).on('load', function() {
            alert($('#msg').val());
        });
    }

    if($('#formContato').length) {
        $('input[name="telefone"]').mask('(99) 9999-9999');
    }

    if($('#chart_div').length) {
        google.charts.load('current', {packages: ['corechart', 'line']});
        var mes = parseMes($('#mes').val());

        montaRelatorio(mes, $('#ano').val());

        $('#mes').change(function() {
            if(this.value) {
                mes = parseMes(this.value);
                montaRelatorio(mes, $('#ano').val());
            }
        });

        $('#ano').change(function() {
            if(this.value) {
                mes = parseMes($('#mes').val());
                montaRelatorio(mes, this.value);
            }
        });
    }

    function parseMes(mes) {
        mes = parseInt(mes);
        return mes < 10 ? '0' + mes : mes;
    }

    function montaRelatorio(mes, ano) {
        var div = document.getElementById('chart_div');
        var relatorio = [];
        $(div).html('');

        var url = 'restrita/dados/getRelatorio?mes=' + mes + '&ano=' + ano;

        if($('#id_profissional').length) {
            url += '&profissional=' + $('#id_profissional').val();
        }

        $.getJSON(siteUrl + url, function(r) {
            relatorio = r;
            google.charts.setOnLoadCallback(drawBackgroundColor);
        });

        function drawBackgroundColor() {
          var data = new google.visualization.DataTable();
          data.addColumn('number', '0');
          data.addColumn('number', 'Visualizações');
          data.addColumn('number', 'Detalhes');

          data.addRows(relatorio);

          var options = {
            legend: 'bottom',
            hAxis: {
              title: 'Dias do Mês'
            },
            vAxis: {
              title: 'Visualizações x Detalhes',
                gridlines: {
                    count: -1
                },
                minValue: 2,
                viewWindow: {
                    min: 0
                },
                format: '0',
            },
            backgroundColor: '#f1f8e9'
          };

          var chart = new google.visualization.LineChart(div);
          chart.draw(data, options);
        }
    }

    if($('#formCadastro').length) {
        $('#tipoCadastro').change(function() {
            if(this.value === 'f') {
                $('#nome').attr('placeholder', 'Nome Completo');
                $('#cpf').attr('name', 'cpf');
                $('#cnpj').attr('name', '');
                $('#cpf').attr('required', 'required');
                $('#cnpj').removeAttr('required');
                $('#cpf').show();
                $('#cnpj').hide();
            } else {
                if($('#cnpj').hasClass('hide')) {
                    $('#cnpj').removeClass('hide');
                }

                $('#nome').attr('placeholder', 'Nome Fantasia');
                $('#cnpj').attr('name', 'cpf');
                $('#cpf').attr('name', '');
                $('#cnpj').attr('required', 'required');
                $('#cpf').removeAttr('required');
                $('#cnpj').show();
                $('#cpf').hide();
            }
        });

    /*    if($('input[name="nome"]').length) {
            $('input[name="nome"]').blur(function() {
                if(this.value) {
                    var nome = this.value;
                    nome = nome.split(' ');

                    if(nome.length === 1) {
                        alert('Digite seu nome completo!');
                        $(this).focus();
                    }
                }
            });
        } */

        if($('#cpf').length) {
            $("#cpf").mask("999.999.999-99");
            $('#cpf').blur(function() {
                var cpf = this.value;
                if(this.value) {
                    cpf = cpf.replace(/[^\d]+/g, '');

                    if(!TestaCPF(cpf)) {
                        alert('Informe um CPF válido!');
                        $(this).val('');
                        $(this).focus();
                    }
                }
            });
        }

        if($('#cnpj').length) {
            $("#cnpj").mask("99.999.999/9999-99");
            $('#cnpj').blur(function() {
                var cnpj = this.value;
                if(this.value) {
                    cnpj = cnpj.replace(/[^\d]+/g, '');

                    if(!validarCNPJ(cnpj)) {
                        alert('Informe um CNPJ válido!');
                        $(this).val('');
                        $(this).focus();
                    }
                }
            });
        }

        if($("#nascimento").length) {
            $("#nascimento").mask("99/99/9999");
        }

        if($("#data_de").length) {
            $("#data_de").mask("99/99/9999");
        }
        

        if($("#data_ate").length) {
            $("#data_ate").mask("99/99/9999");
        }

        if($("#inicio").length) {
            $("#inicio").mask("99/99/9999 99:99:99");
        }

        if($("#fim").length) {
            $("#fim").mask("99/99/9999 99:99:99");
        }

        if($("#celular").length) {
            $("#celular").mask("(99) 9999-9999?9").ready(function(event) {
                var target, phone, element;
                target = document.getElementById('celular');
                phone = target.value.replace(/\D/g, '');
                element = $(target);
                element.unmask();
                if(phone.length > 10) {
                    element.mask("(99) 99999-999?9");
                } else {
                    element.mask("(99) 99999-999?9");
                }
            });
        }
    }

    if($("#telefone").length) {
        $("#telefone").mask("(99) 9999-9999?9").ready(function(event) {
            var target, phone, element;
            target = document.getElementById('telefone');
            phone = target.value.replace(/\D/g, '');
            element = $(target);
            element.unmask();
            if(phone.length > 10) {
                element.mask("(99) 99999-999?9");
            } else {
                element.mask("(99) 9999-9999?9");
            }
        });
    }

    function validarCNPJ(cnpj) {
        var cnpj = cnpj.replace(/[^\d]+/g,'');

        if(cnpj == '') return false;

        if (cnpj.length != 14)
            return false;

        // Elimina CNPJs invalidos conhecidos
        if (cnpj == "00000000000000" ||
            cnpj == "11111111111111" ||
            cnpj == "22222222222222" ||
            cnpj == "33333333333333" ||
            cnpj == "44444444444444" ||
            cnpj == "55555555555555" ||
            cnpj == "66666666666666" ||
            cnpj == "77777777777777" ||
            cnpj == "88888888888888" ||
            cnpj == "99999999999999")
            return false;

        // Valida DVs
        var tamanho,
            numeros,
            digitos,
            resultado,
            soma,
            pos = '';

        tamanho = cnpj.length - 2
        numeros = cnpj.substring(0,tamanho);
        digitos = cnpj.substring(tamanho);
        soma = 0;
        pos = tamanho - 7;

        for(var i = tamanho; i >= 1; i--) {
          soma += numeros.charAt(tamanho - i) * pos--;
          if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(0))
            return false;

        tamanho = tamanho + 1;
        numeros = cnpj.substring(0,tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (i = tamanho; i >= 1; i--) {
          soma += numeros.charAt(tamanho - i) * pos--;
          if (pos < 2)
                pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
        if (resultado != digitos.charAt(1))
              return false;

        return true;

    }

    function TestaCPF(strCPF) {
        var Soma;
        var Resto;
        Soma = 0;

        if(strCPF == "00000000000") {
            return false;
        }

        for(var i = 1; i <= 9; i++) {
            Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
        }

        Resto = (Soma * 10) % 11;

        if((Resto == 10) || (Resto == 11)) {
            Resto = 0;
        }

        if(Resto != parseInt(strCPF.substring(9, 10))) {
            return false;
        }

        Soma = 0;

        for(var i = 1; i <= 10; i++) {
           Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
        }

        Resto = (Soma * 10) % 11;

        if((Resto == 10) || (Resto == 11)) {
            Resto = 0;
        }

        if(Resto != parseInt(strCPF.substring(10, 11))) {
            return false;
        }

        return true;
    }

    $('#minhaTabela').dataTable({
        "language": {
            "search": "Buscar",
            "lengthMenu": "Exibir _MENU_ registros por página",
            "zeroRecords": "Nenhum registro encontrado!",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Nenhum registro disponível!",
            "infoFiltered": "",
            "paginate": {
                "previous": "Anterior",
                "next": "Próxima"
            }
        },
        "order": [[0, 'desc']]
    });



/////////////////////////////////////////////////////////////////
// SETTING
/////////////////////////////////////////////////////////////////

    var windowHeight = $(window).height();
    var windowWidth = $(window).width();


    var tabletWidth = 767;
    var mobileWidth = 640;
	
	
				
	  /////////////////////////////////////
    //  LOADER
    /////////////////////////////////////

    var $preloader = $('#page-preloader'),
    $spinner = $preloader.find('.spinner-loader');
    $spinner.fadeOut();
    $preloader.delay(50).fadeOut('slow');
	
	
	
	  /////////////////////////////////////
    //  Scroll Animation
    /////////////////////////////////////




//	window.sr = ScrollReveal({
//	mobile:true,
//	reset:true
//    }
//	);
//
//	sr.reveal('.wow');
	
	




/////////////////////////////////////
//  Sticky Header
/////////////////////////////////////


    if (windowWidth > tabletWidth) {

        var headerSticky = $(".layout-theme").data("header");
        var headerTop = $(".layout-theme").data("header-top");

        if (headerSticky.length) {
            $(window).on('scroll', function() {
                var winH = $(window).scrollTop();
                var $pageHeader = $('.yamm-wrap');
                if (winH > headerTop) {

                    $('.yamm-wrap').addClass("animated");
                    $('.yamm-wrap').addClass("animation-done");
                    $('.yamm-wrap').addClass("bounce");
                    $pageHeader.addClass('sticky');

                } else {

                    $('.yamm-wrap').removeClass("bounce");
                    $('.yamm-wrap').removeClass("animated");
                    $('.yamm-wrap').removeClass("animation-done");
                    $pageHeader.removeClass('sticky');
                }
            });
        }
    }




/////////////////////////////////////////////////////////////////
//   Dropdown Menu Fade
/////////////////////////////////////////////////////////////////


    $(".dropdown").hover(
        function() {
            $('.dropdown-menu', this).stop(true, true).slideDown("fast");
            $(this).toggleClass('open');
        },
        function() {
            $('.dropdown-menu', this).stop(true, true).slideUp("fast");
            $(this).toggleClass('open');
        }
    );


    $(".yamm .navbar-nav>li").hover(
        function() {
            $('.dropdown-menu', this).fadeIn("fast");
        },
        function() {
            $('.dropdown-menu', this).fadeOut("fast");
        });


    window.prettyPrint && prettyPrint();
    $(document).on('click', '.yamm .dropdown-menu', function(e) {
        e.stopPropagation();
    });



/////////////////////////////////////
//  Disable Mobile Animated
/////////////////////////////////////

    if (windowWidth < mobileWidth) {

        $("body").removeClass("animated-css");

    }


        $('.animated-css .animated:not(.animation-done)').waypoint(function() {

                var animation = $(this).data('animation');

                $(this).addClass('animation-done').addClass(animation);

        }, {
                        triggerOnce: true,
                        offset: '90%'
        });




//////////////////////////////
// Animated Entrances
//////////////////////////////



    if (windowWidth > 1200) {

        $(window).scroll(function() {
                $('.animatedEntrance').each(function() {
                        var imagePos = $(this).offset().top;

                        var topOfWindow = $(window).scrollTop();
                        if (imagePos < topOfWindow + 400) {
                                        $(this).addClass("slideUp"); // slideUp, slideDown, slideLeft, slideRight, slideExpandUp, expandUp, fadeIn, expandOpen, bigEntrance, hatch
                        }
                });
        });

    }




/////////////////////////////////////////////////////////////////
// Accordion
/////////////////////////////////////////////////////////////////

    $(".btn-collapse").on('click', function () {
            $(this).parents('.panel-group').children('.panel').removeClass('panel-default');
            $(this).parents('.panel').addClass('panel-default');
            if ($(this).is(".collapsed")) {
                $('.panel-title').removeClass('panel-passive');
            }
            else {$(this).next().toggleClass('panel-passive');
        };
    });




/////////////////////////////////////
//  Chars Start
/////////////////////////////////////

    if ($('body').length) {
            $(window).on('scroll', function() {
                    var winH = $(window).scrollTop();

                    $('.list-progress').waypoint(function() {
                            $('.chart').each(function() {
                                    CharsStart();
                            });
                    }, {
                            offset: '80%'
                    });
            });
    }


        function CharsStart() {
            $('.chart').easyPieChart({
                    barColor: false,
                    trackColor: false,
                    scaleColor: false,
                    scaleLength: false,
                    lineCap: false,
                    lineWidth: false,
                    size: false,
                    animate: 7000,

                    onStep: function(from, to, percent) {
                            $(this.el).find('.percent').text(Math.round(percent));
                    }
            });

        }




/////////////////////////////////////
//  Zoom Images
/////////////////////////////////////


    $("a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000});



/////////////////////////////////////////////////////////////////
// Accordion
/////////////////////////////////////////////////////////////////

    $(".btn-collapse").on('click', function () {
            $(this).parents('.panel-group').children('.panel').removeClass('panel-default');
            $(this).parents('.panel').addClass('panel-default');
            if ($(this).is(".collapsed")) {
                $('.panel-title').removeClass('panel-passive');
            }
            else {$(this).next().toggleClass('panel-passive');
        };
    });




/////////////////////////////////////////////////////////////////
// Filter accordion
/////////////////////////////////////////////////////////////////


$('.js-filter').on('click', function() {
        $(this).prev('.wrap-filter').slideToggle('slow')});

$('.js-filter').on('click', function() {
        $(this).toggleClass('filter-up filter-down')});


////////////////////////////////////////////
// ISOTOPE FILTER
///////////////////////////////////////////


$(window).load(function() {

    var $container = $('.isotope-filter');

    $container.imagesLoaded(function() {
        $container.isotope({
            // options
            itemSelector: '.isotope-item'
        });
    });

    // filter items when filter link is clicked
    $('#filter a').click(function() {
        var selector = $(this).attr('data-filter');
        $container.isotope({
            filter: selector
        });
        return false;
    });

});



////////////////////////////////////////////
// HOME SLIDER
///////////////////////////////////////////

    if ($('#my-slider').length > 0) {

$( '#my-slider' ).sliderPro({
	    fade: true,
        width: 1600,
        height: 650,
        arrows: true,
        buttons: false,
        waitForLayers: true,
            autoplay: true,
        autoScaleLayers: false
    });

	}

////////////////////////////////////////////
// CAROUSEL PRODUCTS
///////////////////////////////////////////



    if ($('#slider-product').length > 0) {

        // The slider being synced must be initialized first
        $('#carousel-product').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 84,
            itemMargin: 8,
            asNavFor: '#slider-product'
        });

        $('#slider-product').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            sync: "#carousel-product"
        });
    }



/////////////////////////////////////////////////////////////////
//PRICE RANGE
/////////////////////////////////////////////////////////////////


    if ($('#slider-price').length > 0) {


        $("#slider-price").noUiSlider({
                        start: [ 15000, 35000 ],
                        step: 500,
                        connect: true,
                        range: {
                            'min': 0,
                            'max': 50000
                        },

                        // Full number format support.
                        format: wNumb({
                            decimals: 0,
                            prefix: '$'
                        })
                    });
    // Reading/writing + validation from an input? One line.
    $('#slider-price').Link('lower').to($('#slider-price_min'));

    // Write to a span? One line.
    $('#slider-price').Link('upper').to($('#slider-price_max'));

    }




/////////////////////////////////////////////////////////////////
// Sliders
/////////////////////////////////////////////////////////////////


    var Core = {

        initialized: false,

        initialize: function() {

                if (this.initialized) return;
                this.initialized = true;

                this.build();

        },

        build: function() {

        // Owl Carousel

            this.initOwlCarousel();
        },
        initOwlCarousel: function(options) {

                        $(".enable-owl-carousel").each(function(i) {
                            var $owl = $(this);

                            var itemsData = $owl.data('items');
                            var navigationData = $owl.data('navigation');
                            var paginationData = $owl.data('pagination');
                            var singleItemData = $owl.data('single-item');
                            var autoPlayData = $owl.data('auto-play');
                            var transitionStyleData = $owl.data('transition-style');
                            var mainSliderData = $owl.data('main-text-animation');
                            var afterInitDelay = $owl.data('after-init-delay');
                            var stopOnHoverData = $owl.data('stop-on-hover');
                            var min480 = $owl.data('min480');
                            var min768 = $owl.data('min768');
                            var min992 = $owl.data('min992');
                            var min1200 = $owl.data('min1200');

                            $owl.owlCarousel({
                                navigation : navigationData,
                                pagination: paginationData,
                                singleItem : singleItemData,
                                autoPlay : autoPlayData,
                                transitionStyle : transitionStyleData,
                                stopOnHover: stopOnHoverData,
                                navigationText : ["<i></i>","<i></i>"],
                                items: itemsData,
                                itemsCustom:[
                                                [0, 1],
                                                [465, min480],
                                                [750, min768],
                                                [975, min992],
                                                [1185, min1200]
                                ],
                                afterInit: function(elem){
                                            if(mainSliderData){
                                                    setTimeout(function(){
                                                            $('.main-slider_zoomIn').css('visibility','visible').removeClass('zoomIn').addClass('zoomIn');
                                                            $('.main-slider_fadeInLeft').css('visibility','visible').removeClass('fadeInLeft').addClass('fadeInLeft');
                                                            $('.main-slider_fadeInLeftBig').css('visibility','visible').removeClass('fadeInLeftBig').addClass('fadeInLeftBig');
                                                            $('.main-slider_fadeInRightBig').css('visibility','visible').removeClass('fadeInRightBig').addClass('fadeInRightBig');
                                                    }, afterInitDelay);
                                                }
                                },
                                beforeMove: function(elem){
                                    if(mainSliderData){
                                            $('.main-slider_zoomIn').css('visibility','hidden').removeClass('zoomIn');
                                            $('.main-slider_slideInUp').css('visibility','hidden').removeClass('slideInUp');
                                            $('.main-slider_fadeInLeft').css('visibility','hidden').removeClass('fadeInLeft');
                                            $('.main-slider_fadeInRight').css('visibility','hidden').removeClass('fadeInRight');
                                            $('.main-slider_fadeInLeftBig').css('visibility','hidden').removeClass('fadeInLeftBig');
                                            $('.main-slider_fadeInRightBig').css('visibility','hidden').removeClass('fadeInRightBig');
                                    }
                                },
                                afterMove: sliderContentAnimate,
                                afterUpdate: sliderContentAnimate,
                            });
                        });
            function sliderContentAnimate(elem){
                var $elem = elem;
                var afterMoveDelay = $elem.data('after-move-delay');
                var mainSliderData = $elem.data('main-text-animation');
                if(mainSliderData){
                                setTimeout(function(){
                                                $('.main-slider_zoomIn').css('visibility','visible').addClass('zoomIn');
                                                $('.main-slider_slideInUp').css('visibility','visible').addClass('slideInUp');
                                                $('.main-slider_fadeInLeft').css('visibility','visible').addClass('fadeInLeft');
                                                $('.main-slider_fadeInRight').css('visibility','visible').addClass('fadeInRight');
                                                $('.main-slider_fadeInLeftBig').css('visibility','visible').addClass('fadeInLeftBig');
                                                $('.main-slider_fadeInRightBig').css('visibility','visible').addClass('fadeInRightBig');
                                }, afterMoveDelay);
                }
            }
        },

    };

    Core.initialize();

});



/////////////////////////////////////////////////////////////////
// Animated WOW
/////////////////////////////////////////////////////////////////
new WOW().init();
