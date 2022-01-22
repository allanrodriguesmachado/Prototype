$(document).ready(function () {

    const Upload = function (formData) {
        $.spinner.show();
        $.ajax({
            "url": '/portal/uploadForm',
            "method": 'POST',
            "data": formData,
            "processData": false,
            "contentType": false,
        });
        $.spinner.hide();
    }

    $(".panelDados-a-href").ready(function () {
        $.spinner.show();
        Request.ajax(
            "/portal/formVisita",
            {
                'razao_social': $("#razao_social").val(),
            },
            function (response) {
                $.spinner.hide();
                if (response.data[0]) {
                    $("#razao_social").html(response.data[0].razao_social)
                    $("#nome_completo").html(response.data[0].nome_completo)
                    $("#doc1").html(response.data[0].doc1).mask('000.000.000-00')
                    $("#contato").html(response.data[0].ddd_telefone + response.data[0].telefone).mask('(00) 0000-0000')
                    $("#representante_id").val(response.data[0].representante_id)
                }
            }
        )
    });

    $(".btnModalFoto").click(function () {
        $('#modalShowFoto').modal('show');
    });

    $("#btnHideFoto").click(function () {
        $('#modalShowFoto').modal('hide');
    });

    $(".btnModalCartao").click(function () {
        $('#modalShowCartao').modal('show');
    });

    $("#btnHideCartao").click(function () {
        $('#modalShowCartao').modal('hide');
    });

    $(".btnModalDoc").click(function () {
        $('#modalShowDoc').modal('show');
    });

    $("#btnHideDoc").click(function () {
        $('#modalShowDoc').modal('hide');
    });

    $("#btnUploadFoto").click(function () {
        if (!$("#fileFoto").val()) {
            bootbox.alert({
                message: "Selecione um arquivo.",
                size: 'small',
            });
            return;
        }

        let formData = new FormData($('#uploadFoto')[0]);
        formData.append('representante_id', $("#representante_id").val());
        formData.append('tipo_arquivo', 'foto');

        Upload(formData);
    });

    // $("#btnUploadCartao").click(function () {
    //     if (!$("#fileCartao").val()) {
    //         bootbox.alert({
    //             message: "Selecione um arquivo.",
    //             size: 'small',
    //         });
    //         return;
    //     }
    //
    //     let formData = new FormData($('#uploadCartao')[0]);
    //     formData.append('representante_id', $("#representante_id").val());
    //     formData.append('tipo_arquivo', 'cartao');
    //
    //     Upload(formData);
    //
    //     $('#modalShowDoc').modal('hide');
    //
    // });
    //
    // $("#btnUploadDoc").click(function () {
    //
    //     if (!$("#fileDoc").val()) {
    //         bootbox.alert({
    //             message: "Selecione um arquivo..",
    //         });
    //         return;
    //     }
    //
    //     let formData = new FormData($('#uploadDoc')[0]);
    //     formData.append('representante_id', $("#representante_id").val());
    //     formData.append('tipo_arquivo', 'doc');
    //
    //     Upload(formData);
    //
    //     $('#modalShowDoc').modal('hide');
    //
    // });

    $("#btnVisita").click(function () {
        $.spinner.show();
        Request.ajax(
            '/portal/validacaoUpload',
            {
                'representante_id': $("#representante_id").val()
            },
            function (response) {
                $.spinner.hide();
                if (response.success) {
                    $('#uploadVisita').hide();
                    $('#data').show();
                }
            }
        )
    })


    $(function () {
        rome(inline_cal, {time: false, date: true, inputFormat: 'YYYY-MM-DD'}).on('data', function (value) {
            $.spinner.show();
            $("#data_agendamento").val(value)
            $.spinner.hide();
        });
    });

    $(function () {
        $(document).on('click', '.rd-day-body', function () {
            $('#data').hide();
            $('#horario').show();
            $('#cadastro').hide();
        });
    });

    $(".horario").click(function () {
        let horario = $(this).text();

        $.spinner.show();
        Request.ajax(
            "/portal/selectVisita",
            {
                'horario': horario,
                'representante_id': $("#representante_id").val(),
                'data': $("#data_agendamento").val(),
                'nome_completo': $("#nome_completo").html(),
                'razao_social': $("#razao_social").html()
            },
            function (response) {
                if (response.data) {
                    if (response.data[0]) {
                        $('#cadastro').hide();
                        $('#horario').hide();
                        $('#informacoes').hide();
                        $('#confirmacaoVisita').show();
                        $('#horario_agendamento').html(horario);
                        $("#agendamento_data").html($("#data_agendamento").val());
                        $("#nome_completo_agendamento").html($("#nome_completo").html());
                        $("#razao_social_agendamento").html($("#razao_social").html());
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

    $("#btnAgendamento").click(function () {
        $.spinner.show();
        Request.ajax(
            '/portal/cadastrarAgendamento',
            {
                'horario': $('#horario_agendamento').html(),
                'representante_id': $("#representante_id").val(),
                'data': $("#data_agendamento").val(),
                'nome_completo': $("#nome_completo").html(),
                'razao_social': $("#razao_social").html()
            },
            function (response) {
                $.spinner.hide();
                if (response.data) {
                    if (response.data[0]) {
                        $("#confirmeInformacao").hide();
                        $("#confirmeCadastro").show();
                        $("#btnAgendamento").hide();
                        $("#btnConfirmacaoAgendamento").show();
                    }
                }
            }
        )
    })
});

