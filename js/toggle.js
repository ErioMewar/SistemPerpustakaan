var passwordInput = document.getElementById("password");
var togglePasswordButton = document.getElementById("toggle-password");
var confirmPasswordInput = document.getElementById("confirm-password");
var toggleConfirmPasswordButton = document.getElementById("toggle-confirm-password");

togglePasswordButton.addEventListener("click", function () {
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    document.getElementById("hide1").style.display = "block";
    document.getElementById("hide2").style.display = "none";
  } else {
    passwordInput.type = "password";
    document.getElementById("hide1").style.display = "none";
    document.getElementById("hide2").style.display = "block";
  }
});

toggleConfirmPasswordButton.addEventListener("click", function () {
  if (confirmPasswordInput.type === "password") {
    confirmPasswordInput.type = "text";
    document.getElementById("hide3").style.display = "block";
    document.getElementById("hide4").style.display = "none";
  } else {
    confirmPasswordInput.type = "password";
    document.getElementById("hide3").style.display = "none";
    document.getElementById("hide4").style.display = "block";
  }
});
