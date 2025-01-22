function toggleAccordion(element) {
  const content = element.nextElementSibling;
  const icon = element.querySelector('svg');
  content.classList.toggle('hidden');
  icon.classList.toggle('rotate-180');
}
