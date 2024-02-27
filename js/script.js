document.addEventListener("click", function (event) {
  var sidebar = document.getElementById("sidebar");
  var menuIcon = document.querySelector(".menu-icon");

  if (!sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
    closeSidebar();
  }
});

function toggleSidebar() {
  var sidebar = document.getElementById("sidebar");
  if (sidebar.style.left === "0px") {
    sidebar.style.left = "-300px";
  } else {
    sidebar.style.left = "0px";
  }
}

function closeSidebar() {
  var sidebar = document.getElementById("sidebar");
  sidebar.style.left = "-300px";
}
