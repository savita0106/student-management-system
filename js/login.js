$(document).ready(function () {
    $("#login").click(function () {
        let email = $("#email").val();
        let pswd = $("#pswd").val();

        $.ajax({
            url: "../php/login.php",
            type: "POST",
            data: {
                email: email,
                pswd: pswd
            },

            success: function (response) {
                if (response.status == "success") {
                    localStorage.setItem(
                        "name",
                        response.name
                    );

                    localStorage.setItem(
                        "session_id",
                        response.session_id
                    );

                    localStorage.setItem(
                        "email",
                        email
                    );

                    window.location.href =
                    "profile.html";

                } else {
                    $("#result").html(
                        response.message
                    );
                }
            },

            error: function (xhr) {
                $("#result").html(
                    xhr.responseText
                );
            }
        });
    });
});