document.addEventListener("DOMContentLoaded", function () {
  const toggleButton = document.getElementById('toggle-dark-mode');
  if (toggleButton) {
    toggleButton.addEventListener('click', function () {
      document.body.classList.toggle('dark-mode');
    });
  }
});
