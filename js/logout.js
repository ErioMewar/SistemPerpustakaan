var inactivityTimeout = 1800000; // 30 minutes in milliseconds

var inactivityTimer = setTimeout(logoutUser, inactivityTimeout);

function resetInactivityTimer() {
  clearTimeout(inactivityTimer);
  inactivityTimer = setTimeout(logoutUser, inactivityTimeout);
}

function logoutUser() {
  window.location.href = "../../logout.php";
}

document.addEventListener("mousemove", resetInactivityTimer);
document.addEventListener("keydown", resetInactivityTimer);
document.addEventListener("keypress", resetInactivityTimer);
document.addEventListener("scroll", resetInactivityTimer);
