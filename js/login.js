$(document).ready(function () {
    $("#loginForm").submit(function (e) {
        e.preventDefault();

        const email = $("#email").val();
        const password = $("#password").val();

        $.ajax({
            url: "php/login.php",
            method: "POST",
            contentType: "application/json",
            dataType: "json",   // ✅ force JSON parsing
            data: JSON.stringify({ email, password }),
            success: function (resp) {
                console.log("Login response:", resp); // ✅ debug

                if (resp.ok) {
                    // Save user data in localStorage
                    localStorage.setItem("user", JSON.stringify(resp.user));
                    window.location.href = "profile.html"; // ✅ redirect
                } else {
                    alert(resp.error); // ✅ fallback instead of hidden div
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", error);
                alert("Server error while logging in");
            }
        });
    });
});
