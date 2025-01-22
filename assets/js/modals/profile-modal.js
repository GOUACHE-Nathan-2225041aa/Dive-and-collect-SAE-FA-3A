document.addEventListener('DOMContentLoaded', function() {
  // Avatar modification
  const btnModifierAvatar = document.getElementById("editAvatard");
  const btnFermerModal = document.getElementById("fermerModal");
  const modalavatar = document.getElementById("modalavatar");

  if (btnModifierAvatar && btnFermerModal && modalavatar) {
    btnModifierAvatar.onclick = function() {
      modalavatar.classList.remove("hidden");
    }

    btnFermerModal.onclick = function() {
      modalavatar.classList.add("hidden");
    }

    modalavatar.onclick = function(event) {
      if (event.target === modalavatar) {
        modalavatar.classList.add("hidden");
      }
    }
  }

  // Email modification
  const btnfermerModalmail = document.getElementById("fermerModalmail");
  const btnModifierMail = document.getElementById("editmail");
  const modalmail = document.getElementById("modalmail");

  if (btnfermerModalmail && btnModifierMail && modalmail) {
    btnModifierMail.onclick = function() {
      modalmail.classList.remove("hidden");
    }
    btnfermerModalmail.onclick = function() {
      modalmail.classList.add("hidden");
    }
    modalmail.onclick = function(event) {
      if (event.target === modalmail) {
        modalmail.classList.add("hidden");
      }
    }
  }

  // Certificate modification
  const btnfermerModalcertificat = document.getElementById("fermerModalcertificat");
  const btnModifiercertificat = document.getElementById("editcertificat");
  const modalcertificat = document.getElementById("modalcertificat");

  if (btnfermerModalcertificat && btnModifiercertificat && modalcertificat) {
    btnModifiercertificat.onclick = function() {
      modalcertificat.classList.remove("hidden");
    }
    btnfermerModalcertificat.onclick = function() {
      modalcertificat.classList.add("hidden");
    }
    modalcertificat.onclick = function(event) {
      if (event.target === modalcertificat) {
        modalcertificat.classList.add("hidden");
      }
    }
  }

  // Password modification
  const btnfermerModalpassword = document.getElementById("fermerModalpassword");
  const btnModifierpassword = document.getElementById("editpassword");
  const modalpassword = document.getElementById("modalpassword");

  if (btnfermerModalpassword && btnModifierpassword && modalpassword) {
    btnModifierpassword.onclick = function() {
      modalpassword.classList.remove("hidden");
    }
    btnfermerModalpassword.onclick = function() {
      modalpassword.classList.add("hidden");
    }
    modalpassword.onclick = function(event) {
      if (event.target === modalpassword) {
        modalpassword.classList.add("hidden");
      }
    }
  }

  // Spot Admin
  const btnfermerProfilSpot = document.getElementById("fermerProfilSpot");
  const btnSpotAdmin = document.getElementById("sendSpotAdmin");
  const modalProfilSpot = document.getElementById("modalProfilSpot");

  if (btnfermerProfilSpot && btnSpotAdmin && modalProfilSpot) {
    btnSpotAdmin.onclick = function() {
      modalProfilSpot.classList.remove("hidden");
    }
    btnfermerProfilSpot.onclick = function() {
      modalProfilSpot.classList.add("hidden");
    }
    modalProfilSpot.onclick = function(event) {
      if (event.target === modalProfilSpot) {
        modalProfilSpot.classList.add("hidden");
      }
    }
  }
});
