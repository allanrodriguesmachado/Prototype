$(document).ready(function () {
    $("#agendamento").ready(function () {
        $.spinner.show();
        Request.ajax(
            '/portal/formAgendamentos',
            {
                'representante_id': 'd64de4ee-b923-4ebc-9e60-407b9daab495',
                'agendamento_id': $('.agendamento_id').val(),
                'nome_completo': $('#nome_completo').val(),
                'data': $('.data_agendamento').html(),
                'horario': $('.horario_agendamento').html(),
                'statusAgendamento': $('#statusAgendamento').val()
            },
            function (response) {
                $.spinner.hide();
                if (response.data[0]) {
                    $('#nome_completo').val(response.data[0].nome_completo),
                        table.rows.add(response.data).draw();
                }
            }
        );
    })

    const excluirUsuario = function (agendamento_id, nome_completo) {
        $.spinner.show();
        Request.ajax(
            "/portal/cancelarAgendamento",
            {
                'agendamento_id': agendamento_id,
                'nome_completo': nome_completo,
            },
            function (response) {
                $.spinner.hide();
                if (response.data) {
                    table.row().remove().draw();
                }
            },
        )
    }

    $('#table').on("click", '.btnExcluir', function () {
        let data = table.row($(this).parents('tr')).data();
        bootbox.confirm({
            message: "Deseja realmente cancelar o seu agendamento?",
            buttons: {
                confirm: {
                    label: 'Sim',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'Não',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result == true) {
                    excluirUsuario(data.agendamento_id, data.nome_completo)
                }
            }
        });
    });

    const table = $('#table').DataTable({
        "stateSave": true,
        "bFilter": false,
        "scrollX": true,
        "paging": false,
        "ordering": true,
        "info": false,
        "order": [[0, "asc"]],
        "columnDefs": [
            {className: "text-nowrap text-left ", "targets": [0]},
            {
                "targets": 4,
                type: 'date-uk',
                "data": null,
                "defaultContent":
                    "<button id='btnExcluir' class='btn btn-danger btn-sm m-1 btnExcluir'><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"15\" fill=\"currentColor\" class=\"bi bi-trash\" viewBox=\"0 0 16 16\">\n" +
                    "  <path d=\"M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z\"/>\n" +
                    "  <path fill-rule=\"evenodd\" d=\"M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z\"/>\n" +
                    "</svg>" +
                    "Cancelar" +
                    "</button>"
            }
        ],
        columns: [
            {data: 'agendamento_id'},
            {data: 'nome_completo'},
            {data: 'data'},
            {data: 'horario'},
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
        }
    });
});
