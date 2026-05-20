$(document).ready(function () {
    $("#logout").click(function () {
        let session_id = localStorage.getItem("session_id");

        $.ajax({
            url: "../php/logout.php",
            type: "POST",
            data: {
                session_id: session_id
            },

            success: function (response) {
                localStorage.removeItem("session_id");
                localStorage.removeItem("email");
                localStorage.removeItem("name");

                window.location.href = "index.html";
            },

            error: function () {
                localStorage.removeItem("session_id");
                localStorage.removeItem("email");
                localStorage.removeItem("name");

                window.location.href = "index.html";
            }
        });
    });
});