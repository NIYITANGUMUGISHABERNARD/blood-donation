// Dashboard JavaScript
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const menuToggle = document.getElementById("menuToggle");
  const overlay = document.getElementById("overlay");

  // Toggle mobile menu
  if (menuToggle) {
    menuToggle.addEventListener("click", function () {
      sidebar.classList.toggle("-translate-x-full");
      overlay.classList.toggle("opacity-0");
      overlay.classList.toggle("invisible");
      overlay.classList.toggle("opacity-100");
      overlay.classList.toggle("visible");
    });
  }

  // Close menu on overlay click
  if (overlay) {
    overlay.addEventListener("click", function () {
      sidebar.classList.add("-translate-x-full");
      overlay.classList.remove("opacity-100", "visible");
      overlay.classList.add("opacity-0", "invisible");
    });
  }

  // Modal handling
  const modals = document.querySelectorAll(".modal-trigger");
  modals.forEach((trigger) => {
    trigger.addEventListener("click", function (e) {
      e.preventDefault();
      const modalId = this.getAttribute("data-modal");
      const modal = document.getElementById(modalId);
      if (modal) {
        modal.classList.remove("opacity-0", "invisible");
        modal.classList.add("opacity-100", "visible");
      }
    });
  });

  const closeButtons = document.querySelectorAll(".modal-close");
  closeButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      const modal = this.closest(".modal");
      if (modal) {
        modal.classList.remove("opacity-100", "visible");
        modal.classList.add("opacity-0", "invisible");
      }
    });
  });
});
