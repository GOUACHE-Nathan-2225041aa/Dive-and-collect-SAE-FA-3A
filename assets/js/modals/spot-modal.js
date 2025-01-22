document.addEventListener('DOMContentLoaded', function() {
  const fermerintervention = document.getElementById("fermerintervention");
  const interventionBtn = document.getElementById("interventionBtn");
  const modalIntervention = document.getElementById("modalIntervention");

  if (fermerintervention && interventionBtn && modalIntervention) {
    interventionBtn.classList.remove("hidden");

    interventionBtn.onclick = function() {
      modalIntervention.classList.remove("hidden");
      interventionBtn.classList.add("hidden");
    }

    fermerintervention.onclick = function() {
      modalIntervention.classList.add("hidden");
      interventionBtn.classList.remove("hidden");
    }
  }
});
