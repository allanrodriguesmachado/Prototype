$(document).ready(function () {
    $("#btnEntrar").click(function () {
        if (!$('#username').val() || !$('#password').val()) {
            bootbox.alert({
                message: "Usuário ou Senha não informado(s) ou inválido(s)",
            });
            return;
        }

        $.spinner.show();
        Request.ajax(
            "/login",
            {
                'username': $("#username").val(),
                'password': $("#password").val(),
            },
            function (response) {

                if (response.success) {
                    window.location.replace('/portal/');
                    return;
                }
            },
            function (response) {
                $.spinner.hide();
                message: response.message
                return;
            },
        )
    });
});

