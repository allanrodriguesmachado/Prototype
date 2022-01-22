$(document).ready(function () {
    $('#cnpj').mask('99.999.999/9999-99');
    $('#doc1').mask('999.999.999-99');
    $('#empresa_ddd_telefone').mask('(00)');
    $('#empresa_telefone').mask('0000-0000');
    $('#empresa_ddd_celular').mask('(00)');
    $('#empresa_celular').mask('00000-0000');
    $('#usuario_ddd_telefone').mask('(00)');
    $('#usuario_telefone').mask('0000-0000');
    $('#usuario_ddd_celular').mask('(00)');
    $('#usuario_celular').mask('00000-0000');
    $('#cep').mask('00000-000');
    $('#frequencia').mask('000');

    function limpa_cep() {
        $("#rua").val("");
        $('#rua').prop("disabled", false);
        $("#numero").val("");
        $('#numero').prop("disabled", false);
        $("#complemento").val("");
        $('#complemento').prop("disabled", false);
        $("#bairro").val("")
        $('#bairro').prop("disabled", false);
        $("#municipio").val("")
        $('#municipio').prop("disabled", false);
        $("#estado").val("")
        $('#estado').prop("disabled", false);
        $("#cidade").val("")
        $('#cidade').prop("disabled", false);
    }

    $("#btnPesquisarCep").click(function () {
        if (!$('#cep').val() || $('#cep').val().length < 9) {
            bootbox.alert({
                message: "Preencha um CEP válido.",
                size: 'small',
            });
            return
        }
    });

    $("#btnFilter").click(function () {
        if (!$('#cnpj').val() || $('#cnpj').val().length <= 8) {
            bootbox.alert({
                message: "CNPJ Inválido",
                size: 'small',
            });
            return;
        }

        $.spinner.show();
        Request.ajax(
            "/portal/getCompany",
            {
                'cnpj': $("#cnpj").val(),
            },
            function (response) {
                $.spinner.hide();
                if (response.data) {
                    if (response.data[0]) {
                        $("#empresa_id").val(response.data[0].empresa_id);
                        $("#id_endereco").val(response.data[0].endereco_id);
                        $('#FormCadastroUsuario').show();
                        $('#cnpj').attr("hidden", false);
                        $('#btnFilter').attr("hidden", true);
                        $('#infoCnpj').attr("hidden", true);
                        $('.removeClassCnpj').attr("hidden", true);
                        $("div").removeClass("center-cnpj");
                        $("div").removeClass("pesquisar");
                        $("div").removeClass("center-main");
                        $("section").addClass("container-main");
                        $('#addClass').show();
                    } else {
                        $('#cnpj').attr("hidden", true);
                        $('#btnFilter').attr("hidden", true);
                        $('#infoCnpj').attr("hidden", true);
                        $('.removeClassCnpj').attr("hidden", true);
                        $("div").removeClass("center-cnpj");
                        $("div").removeClass("pesquisar");
                        $("div").removeClass("center-main");
                        $("section").addClass("container-main");
                        $('#addClass').show();
                        $('#displayForm').show();
                    }
                }
            },
            function (response) {
                $.spinner.hide();
                bootbox.alert({
                    message: response.message
                });
                return;
            }
        )
    });

    $("#btnCadastrarEmpresa").click(function () {
        $.spinner.show();
        Request.ajax(
            "/portal/createCompany",
            {
                'data': $("#FormCadastro").serialize(),
                'cnpj': $("#cnpj").val().replace(/[^\d]+/gi, ''),
                'cep': $("#cep").val().replace(/[^\d]+/gi, ''),
                'rua': $("#rua").val(),
                'bairro': $("#bairro").val(),
                'municipio': $("#municipio").val(),
                'estado': $("#estado").val(),
                'cidade': $("#cidade").val(),
                'complemento': $("#complemento").val(),
                'numero': $("#numero").val(),
            },
            function (response) {
                $.spinner.hide();
                if (response.data) {
                    $("#empresa_id").val(response.data[0].empresa_id);
                    $("#id_endereco").val(response.data[0].endereco_id);
                    $('#FormCadastroUsuario').show();
                    $('#FormCadastro').attr("hidden", true);
                }
            }
        )
    });

    $("#cep").keyup(function () {
        if ($('#cep').val().length < 9) {
            return
        }

        if (!$('#cep').val() || $('#cep').val().length < 9) {
            bootbox.alert({
                message: "Preencha um CEP válido.",
                size: 'small',
            });
            return;
        }

        $.spinner.show();
        limpa_cep();
        Request.ajax(
            "/portal/httpClientValidationCep",
            {
                'cep': $("#cep").val(),
            },
            function (response) {
                $.spinner.hide();
                if (response.data.length > 0) {
                    $("#rua").val(response.data[0].endereco);
                    $("#bairro").val(response.data[0].bairro);
                    $("#municipio").val(response.data[0].cidade);
                    $("#estado").val(response.data[0].cidade_uf);
                    $("#cidade").val(response.data[0].estado);
                    $('#rua').prop("disabled", true);
                    $('#bairro').prop("disabled", true);
                    $('#municipio').prop("disabled", true);
                    $('#estado').prop("disabled", true);
                    $('#cidade').prop("disabled", true);
                } else {
                    limpa_cep();
                }
            },
            function (response) {
                $.spinner.hide();
                bootbox.alert({
                    message: response.message
                });
                return;
            }
        )
    })

    $("#doc1").keyup(function () {

        if ($('#doc1').val().length < 14) {
            return
        }

        if (!$('#doc1').val() || $('#doc1').val().length < 14) {
            bootbox.alert({
                message: "Preencha um CPF válido.",
                size: 'small',
            });
            return
        }

        $.spinner.show();
        Request.ajax(
            "/portal/getRepresentative",
            {
                'data': $("#FormCadastroUsuario").serialize()
            },
            function (response) {
                $.spinner.hide();
                if (response.data[0]) {
                    $("#representante_id").val(response.data[0].id)
                    bootbox.alert({
                        message: "Representante já Cadastrado.",
                        size: 'small',
                    });
                    $('#representante_id').prop("disabled", true);
                }
            }
        )
    })

    $("#btnNovoUsuario").click(function () {

        if (!$('#doc1').val() || $('#doc1').val().length < 14) {
            bootbox.alert({
                message: "Preencha um CPF válido.",
                size: 'small',
            });
            return
        }

        if ($('#representante_id').val()) {
            bootbox.alert({
                message: "Representante já cadastrado.",
                size: 'small',
            });
            return
        }

        $.spinner.show();
        Request.ajax(
            "/portal/createRepresentative",
            {
                'data': $("#FormCadastroUsuario").serialize(),
            },
            function (response) {
                $.spinner.hide();
                $('#infoLogin').show();
                $('#registerHidden').hide();
                // bootbox.alert({
                //     message: "Cadastro realizado com sucesso! OBS Seu cadastro passara por uma avaliação, assim que estiver tudo certo, você recebera um e-mail, com o login e senha de acesso!",
                // });
                // window.location.replace('/');
                // return;

            }
        )
    });

    $('#toggleDados').on('click', function () {
        $('#panelDados').collapse('toggle');
    });

    $('#toggleDados2').on('click', function () {
        $('#panelDados2').collapse('toggle')
    });

    $('#toggleDados3').on('click', function () {
        $('#panelDados3').collapse('toggle');
    });

});
