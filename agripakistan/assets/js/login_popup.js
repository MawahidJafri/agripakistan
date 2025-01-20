$(document).ready(function () {
    $('#loginForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default submission

        const formData = {
            email: $('#loginEmail').val(),
            password: $('#loginPassword').val(),
        };

        $.ajax({
            url: 'login_popup.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                const messageDiv = $('#loginMessage');
                if (response.status === 'success') {
                    messageDiv.css('color', 'green').text(response.message);
                    setTimeout(() => {
                        window.location.href = 'index.php'; // Redirect to the homepage
                    }, 1500);
                } else {
                    messageDiv.css('color', 'red').text(response.message);
                }
            },
            error: function () {
                $('#loginMessage').css('color', 'red').text('An error occurred. Please try again.');
            },
        });
    });
});
