// Menú móvil
document
  .querySelector(".mobile-menu-btn")
  .addEventListener("click", function () {
    document.querySelector(".nav-links").classList.toggle("active");
  });

// Cerrar menú al hacer clic en enlace
document.querySelectorAll(".nav-links a").forEach((link) => {
  link.addEventListener("click", function () {
    document.querySelector(".nav-links").classList.remove("active");
  });
});

// Smooth scroll para enlaces internos
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();

    const targetId = this.getAttribute("href");
    if (targetId === "#") return;

    const targetElement = document.querySelector(targetId);
    if (targetElement) {
      window.scrollTo({
        top: targetElement.offsetTop - 80,
        behavior: "smooth",
      });
    }
  });
});
