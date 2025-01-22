document.addEventListener('DOMContentLoaded', function () {
  const editDiveBtn = document.getElementById("edit-dive-btn");
  const diveModal = document.getElementById("dive-modal");
  const closeModalBtn = document.getElementById("close-btn");
  const deleteDiveBtn = document.getElementById("delete-dive-btn");
  const deleteModal = document.getElementById("confirm-removal");
  const closeDeleteBtn = document.getElementById("cr-close-btn");
  const cancelBtn = document.getElementById("cancel-removal");

  if (editDiveBtn && diveModal && closeModalBtn) {
    editDiveBtn.onclick = function () {
      diveModal.classList.remove("hidden");
    }

    closeModalBtn.onclick = function () {
      diveModal.classList.add("hidden");
    }

    diveModal.onclick = function (e) {
      if (e.target === diveModal) {
        diveModal.classList.add("hidden");
      }
    }
  }

  if (deleteDiveBtn && deleteModal && closeDeleteBtn && cancelBtn) {
    deleteDiveBtn.onclick = function () {
      deleteModal.classList.remove("hidden");
    }

    function hideModal() {
      deleteModal.classList.add("hidden");
    }

    cancelBtn.onclick = closeDeleteBtn.onclick = hideModal;

    deleteModal.onclick = function (e) {
      if (e.target === deleteModal) {
        hideModal();
      }
    }
  }
});
