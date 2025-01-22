document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('searchForm').querySelector('input[name="search"]');
  const diveList = document.getElementById('diveList');
  const diveItems = diveList.querySelectorAll('.dive-item');

  searchInput.addEventListener('input', function () {
    const searchText = searchInput.value.toLowerCase();

    diveItems.forEach(item => {
      const title = item.querySelector('.text-xl').textContent.toLowerCase();
      if (title.includes(searchText)) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  });
});
