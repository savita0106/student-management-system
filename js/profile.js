$(document).ready(function () {

    console.log("profile js loaded");

    $("#saveProfile").click(function () {

        console.log("save button clicked");

    });

    let session_id = localStorage.getItem("session_id");

    if (session_id == null) {

        window.location.href = "login.html";

    }

    let name = localStorage.getItem("name");

    $("#welcomeName").html(
        "Welcome " + name + "!"
    );

    $.ajax({

        url: "../php/profile.php",

        type: "POST",

        data: {
            action: "load_profile",
            session_id: session_id
        },

        success: function (response) {

            if (response.status == "success") {

                $("#age").val(response.age);
                $("#dob").val(response.dob);
                $("#contact").val(response.contact);

                $("#profileData").html(
                    "<h4>saved details</h4>" +
                    "<p>age: " + response.age + "</p>" +
                    "<p>dob: " + response.dob + "</p>" +
                    "<p>contact: " + response.contact + "</p>"
                );

            }

        }

    });

    $("#saveProfile").click(function () {

        let age = $("#age").val();
        let dob = $("#dob").val();
        let contact = $("#contact").val();

        $.ajax({

            url: "../php/profile.php",

            type: "POST",

            data: {
                action: "save_profile",
                session_id: session_id,
                age: age,
                dob: dob,
                contact: contact
            },

            success: function (response) {

                if (response.status == "success") {

                    $("#result").html("profile saved");

                    $("#profileData").html(
                        "<h4>saved details</h4>" +
                        "<p>age: " + age + "</p>" +
                        "<p>dob: " + dob + "</p>" +
                        "<p>contact: " + contact + "</p>"
                    );

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

$("#updateProfile").click(function () {

    console.log("update button clicked");

    let session_id = localStorage.getItem("session_id");

    let age = $("#age").val();
    let dob = $("#dob").val();
    let contact = $("#contact").val();
    
    $.ajax({

        url: "../php/profile.php",

        type: "POST",

        data: {
            action: "save_profile",
            session_id: session_id,
            age: age,
            dob: dob,
            contact: contact
        },

        success: function (response) {

            if (response.status == "success") {

                $("#result").html("profile updated");

                $("#profileData").html(
                    "<h4>Saved details:</h4>" +
                    "<p>age: " + age + "</p>" +
                    "<p>dob: " + dob + "</p>" +
                    "<p>contact: " + contact + "</p>"
                );

            } else {

                $("#result").html(response.message);

            }

        },

        error: function (xhr) {

            $("#result").html(xhr.responseText);

        }

    });

});

