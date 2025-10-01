$(function () {
  const strengthMsg = $("#strengthMessage");


  $("input[name=password]").on("input", function () {
    const pwd = this.value;
    const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;
    const mediumRegex = /^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[A-Z])(?=.*\d))|((?=.*[a-z])(?=.*\d))).{6,}$/;

    if (strongRegex.test(pwd)) {
      strengthMsg.text("Strong password ✅").css("color", "green");
    } else if (mediumRegex.test(pwd)) {
      strengthMsg.text("Medium password ⚠️").css("color", "orange");
    } else {
      strengthMsg.text("Weak password ❌").css("color", "red");
    }
  });

  $("#registerForm").on("submit", function (e) {
    e.preventDefault();

    const name = this.name.value.trim();
    const email = this.email.value.trim();
    const password = this.password.value;
    const confirm = this.confirm_password.value;

    if (password !== confirm) {
      alert("Passwords do not match");
      return;
    }

    const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;
    if (!strongRegex.test(password)) {
      alert("Password must be strong (8+ chars, upper, lower, number, special)");
      return;
    }

    const payload = { name, email, password };

    $.ajax({
      url: "php/register.php",
      method: "POST",
      data: JSON.stringify(payload),
      contentType: "application/json",
      success: () => {
        window.location.href = "login.html";
      },
      error: (xhr) => {
        const msg = xhr.responseJSON?.error || "Registration failed";
        alert(msg);
      },
    });
  });
});
