$(document).ready(function () {

    $("#register").click(function () {

        let name = $("#name").val();
        let email = $("#email").val();
        let pswd = $("#pswd").val();

        $.ajax({

            url: "../php/register.php",

            type: "POST",

            data: {
                name: name,
                email: email,
                pswd: pswd
            },

            success: function (response) {

                console.log(response);

                if (response.status == "success") {

                    localStorage.setItem(
                        "name",
                        name
                    );

                    localStorage.setItem(
                        "session_id",
                        response.session_id
                    );

                    localStorage.setItem(
                        "email",
                        email
                    );

                    window.location.href = "profile.html";

                } else {

                    $("#result").html(response.message);

                }

            },

            error: function (xhr) {

                $("#result").html(xhr.responseText);

            }

        });

    });

});