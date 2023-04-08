$(function () {

    $("#contactForm input, #contactForm textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function ($form, event, errors) {},
        submitSuccess: function ($form, event) {
            event.preventDefault();
            var name = $("input#name").val();
            var email = $("input#email").val();
            var subject = $("input#subject").val();
            var message = $("textarea#message").val();

            $("#sendMessageButton").prop("disabled", true);
            $("#sendMessageButton span").text("SENDING...");
            $("#sendMessageButton div").removeClass("d-none");

            $.ajax({
                url: 'mail/contact-form.php',
                type: "POST",
                data: {
                    name: name,
                    email: email,
                    subject: subject,
                    message: message
                },
                cache: false,
                success: function () {
                    $('#alertMessage').html("<div class='alert alert-success alert-dismissible'>");
                    $('#alertMessage > .alert-success').html("<button type='button' class='btn-close' data-bs-dismiss='alert' aria-hidden='true'>").append("</button>");
                    $('#alertMessage > .alert-success').append("<strong>" + name + ", your message has been sent. </strong>");
                    $('#alertMessage > .alert-success').append('</div>');
                    $('#contactForm').trigger("reset");
                },
                error: function () {
                    $('#alertMessage').html("<div class='alert alert-danger alert-dismissible'>");
                    $('#alertMessage > .alert-danger').html("<button type='button' class='btn-close' data-bs-dismiss='alert' aria-hidden='true'>").append("</button>");
                    $('#alertMessage > .alert-danger').append($("<strong>").text("Sorry " + name + ", it seems that our mail server is not responding. Please try again later!"));
                    $('#alertMessage > .alert-danger').append('</div>');
                },
                complete: function () {
                    $("#sendMessageButton").prop("disabled", false);
                    $("#sendMessageButton span").text("SEND");
                    $("#sendMessageButton div").addClass("d-none");
                }
            });
        },
    });
});

$('#name, #email, #subject, #message').focus(function () {
    $('#alertMessage').html('');
});