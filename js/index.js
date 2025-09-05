document.addEventListener("DOMContentLoaded", function () {
  const menuIcon = document.getElementById("icon_menu");
  const subNav = document.getElementById("sub_nav");

  menuIcon.addEventListener("click", function () {
      console.log("menu geklikt"); // check in devtools
  subNav.classList.toggle("active");
   
  });

  document.querySelectorAll("#sub_nav a").forEach(link => {
    link.addEventListener("click", () => {
      subNav.classList.remove("active");
    });
  });
});

