$(function() {
    var siteUrl = $('#siteUrl').val();

    if($('.summernote').length) {
        $('.summernote').summernote({
            callbacks: {
                onChange: function(contents) {
                    $('#texto').val(contents);
                }
          }
        });

        $('#texto').val($('.summernote').summernote('code'));
    }

    if($('.status').length) {
        var status = $('.status');

        $(status).change(function() {
            $.post(siteUrl + 'restrita/home/alteraStatus', {
                'csrf_test_name': $.cookie('csrf_cookie_name'),
                'campo': $(this).data('campo'),
                'id': $(this).data('id'),
                'tabela': $(this).data('tabela'),
                'status': this.value
            });
        });
    }

    if($('.situacao').length) {
        var situacao = $('.situacao');

        $(situacao).change(function() {
            $.post(siteUrl + 'restrita/home/alteraSituacao', {
                'csrf_test_name': $.cookie('csrf_cookie_name'),
                'campo': $(this).data('campo'),
                'id': $(this).data('id'),
                'tabela': $(this).data('tabela'),
                'situacao': this.value
            });
        });
    }

    if($('.galeria').length) {
        $('.galeria').sortable({
            update: function(event, ui){
                atualizaOrdem();
            }
        });

        $('.galeria').disableSelection();
    }

	function atualizaOrdem() {
        var fotos = [];

		$.each($('.galeria img'),function(i,obj){
			var id = $(obj).attr('data-id');

			$('#foto'+id).val(i);
            fotos[i] = {
                ordem: i,
                id: id
            }
		});

        $.post(siteUrl + 'restrita/home/ordenaGaleria', {
            'csrf_test_name': $.cookie('csrf_cookie_name'),
            fotos: fotos
        });
	}

    if(typeof Dropzone !== 'undefined') {
        var fileName = "galeria";

        Dropzone.options.myAwesomeDropzone = {
            paramName: fileName,
            acceptedFiles: 'image/*',
            maxFilesize: $('#maxFilesize').val(),
            dictFileTooBig: "A imagem excedeu o limite ("+$('#maxFilesize').val()+'MB) !',
            parallelUploads: 1,
            maxFiles: parseInt($('#maxFotos').html()),
            params: {
                csrf_test_name: $.cookie('csrf_cookie_name'),
                id: $('#id').val(),
                nome: $('#nome').val(),
                inputName: fileName
            },
            init: function() {
                this.on('maxfilesexceeded', function(file){
                    this.removeFile(file);
                });

                this.on('addedfile', function(file) {
                    var maxFotos = parseInt($('#maxFotos').html());

                    if(maxFotos > 0) {
                        this.options.maxFiles = $('input[id="maxFotos"]').val();
                        $('#maxFotos').html(maxFotos - 1);
                    }
                });

                this.on('queuecomplete', function() {
                    location.reload(true);
                });
            }
        };
    }

    if($('.deletar').length) {
        var deletar = $('.deletar');
        var campo,
            id,
            definitivo,
            tabela = '';

        $(deletar).click(function() {
            campo = $(this).data('campo');
            id = $(this).data('id');
            definitivo = $(this).data('definitivo');
            tabela = $(this).data('tabela');
        });

        $('#confDelete').click(function() {
            $.post(siteUrl + 'restrita/home/deleta', {
                'csrf_test_name': $.cookie('csrf_cookie_name'),
                'campo': campo,
                'id': id,
                'tabela': tabela,
                'definitivo': definitivo,
                'status': this.value
            }, function() {
                location.reload(true);
            });
        });
    }

    if($('.excluirFoto').length) {
        var deletar = $('.excluirFoto');
        var campo,
            id,
            foto,
            tabela = '';

        $(deletar).click(function(e) {
            e.preventDefault();
            campo = $(this).data('campo');
            id = $(this).data('id');
            tabela = $(this).data('tabela');
            foto = $(this).data('foto');
        });

        $('#confDelete').click(function() {
            $.post(siteUrl + 'restrita/home/excluiFoto', {
                'csrf_test_name': $.cookie('csrf_cookie_name'),
                'campo': campo,
                'id': id,
                'foto': foto,
                'tabela': tabela,
                'status': this.value
            }, function() {
                location.reload(true);
            });
        });
    }

    if($('.excluirFotoGaleria').length) {
        var deletar = $('.excluirFotoGaleria');

        $(deletar).click(function(e) {
            e.preventDefault();
            $('#btnExcluirFoto').data('id', $(this).data('id'));
            $('#btnExcluirFoto').data('foto', $(this).data('foto'));
        });

        $('#btnExcluirFoto').click(function() {
            var id = $(this).data('id');
            var foto = $(this).data('foto');

            $.post(siteUrl + 'restrita/home/excluirFotoGaleria', {
                'csrf_test_name': $.cookie('csrf_cookie_name'),
                id: id,
                foto: foto
            }, function() {
                $('div[data-id="' + id +'"]').remove();
                $('#excluir').modal('hide');

                var maxFotos = parseInt($('#maxFotos').html());
                $('#maxFotos').html(maxFotos + 1);
                $('input[id="maxFotos"]').val(maxFotos + 1);

                if(maxFotos === 0) {
                    $('.quadroFotos').removeClass('hide');
                }
            });
        });
    }

    $(".pop").on("click", function() {
        $('#imagepreview').attr('src', $(this).attr('src') || $(this).data('src'));
        $('#imageModal').modal('show');
    });
})
