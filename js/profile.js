$(document).ready(function () {
    const user = JSON.parse(localStorage.getItem("user"));

    if (!user) {
        alert("Not logged in. Redirecting to login.");
        window.location.href = "login.html";
        return;
    }

    // Populate fields with user data
    $("#userId").text(user.id);
    $("#userName").text(user.name);
    $("#userEmail").text(user.email);

    $("#age").val(user.age || "");
    $("#dob").val(user.dob || "");
    $("#contact").val(user.contact || "");
    $("#address").val(user.address || "");

    // Save profile
    $("#profileForm").submit(function (e) {
        e.preventDefault();

        const updatedData = {
            id: user.id,
            age: $("#age").val(),
            dob: $("#dob").val(),
            contact: $("#contact").val(),
            address: $("#address").val()
        };

        $.ajax({
            url: "php/profile.php",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(updatedData),
            success: function (resp) {
                if (resp.ok) {
                    alert("Profile updated successfully");
                    // Update localStorage with latest data
                    localStorage.setItem("user", JSON.stringify(resp.user));
                } else {
                    alert(resp.error);
                }
            },
            error: function () {
                alert("Server error while updating profile");
            }
        });
    });

    // Logout
    $("#logoutBtn").click(function () {
        localStorage.removeItem("user");
        window.location.href = "login.html";
    });
});
