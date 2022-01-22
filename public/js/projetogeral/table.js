$(document).ready(function () {
    $(".dt-buttons").click(function () {
        $('#modalShowTable').modal('show');
    });

    $("#dt-buttons").click(function () {
        $('#modalShowTable').modal('hide');
    });

    $("#btnHideTable").click(function () {
        $('#modalShowTable').modal('hide');
    });

    const Upload = function (formData) {
        $.ajax({
            "url": '/portal/importarExcel',
            "method": 'POST',
            "data": formData,
            "processData": false,
            "contentType": false,
        });
    }

    const UploadCsv = function (uploadCsv) {
        var leitorDeCSV = new FileReader();

        window.onload = function init() {
            leitorDeCSV.onload = leCSV;
        }

        function pegaCSV(inputFile) {
            var file = inputFile.files[0];
            leitorDeCSV.readAsText(file);
        }

        function leCSV(evt) {

            var fileArr = evt.target.result.split('\n');
            var strDiv = '<table>';

            for (var i = 0; i < fileArr.length; i++) {
                strDiv += '<tr>';
                var fileLine = fileArr[i].split(',');
                for (var j = 0; j < fileLine.length; j++) {
                    strDiv += '<td>' + fileLine[j].trim() + '</td>';
                }
                strDiv += '</tr>';
            }

            strDiv += '</table>';

            var CSVsaida = document.getElementById('CSVsaida');
            CSVsaida.innerHTML = strDiv;
        }
    }

    $("#btnTable").click(function () {
        let formData = new FormData($('#uploadTable')[0]);
        formData.append('representante_id', $("#representante_id").val());

        Upload(formData);
        UploadCsv(uploadCsv);

        $('#modalShowTable').modal('hide');
    });

    $('#tablePortal').DataTable({
        "stateSave": true,
        "bFilter": false,
        "paging": false,
        "ordering": true,
        "info": false,
        "targets": [1, 2, 3],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: 'Exportar Excel',
                exportOptions: {
                    modifier: {
                        selected: null
                    }
                }
            },
        ],
        "language":
            {
                "url":
                    "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
            }
    });


    let dt = $('#createTable').DataTable({
        paging: false,
        bFilter: false,
        bInfo: false,
        orderable: false,
    });

    // $('#addRow').click(function () {
    //     let newRow = $("#newRow").find("tr")[0].outerHTML
    //     dt.rows.add($(newRow)).draw();
    //     console.log(newRow);
    // });
    //
    // $('#button').click(function () {

        // var itens = dt.data().toArray();
        // var employees = {
        //     itens: []
        // };
        //
        //
        // $.each(itens, function (key, value) {
        //     employees.itens.push({
        //         "ean": value.ean
        //     });
        // });
        //
        // if (employees.itens.length == 0) {
        //     bootbox.alert('Pedido sem mercadoria!');
        //     return;
        // }
        // console.log(employees.itens);

    //     let data ;
    //     console.log(data);
    //
    // });


    $("#btnSalvarTabela").click(function () {
        Request.ajax(
            "/portal/createTable",
            {
                'data': $("#criarTabela").serialize(),
            },
            function (response) {
                if (response.data) {
                   console.log(response.data);
                }
            }
        )
    })
});
