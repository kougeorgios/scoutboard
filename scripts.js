function openTab(tabId) {
  document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
  document.querySelectorAll('.tab-button').forEach(el => el.classList.remove('active'));

  document.getElementById(tabId).classList.remove('hidden');
  event.target.classList.add('active');
}
