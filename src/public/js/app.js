// Affiche le champ « adresse » uniquement pour la livraison
document.addEventListener('DOMContentLoaded', function () {
  var radios = document.querySelectorAll('input[name="delivery_method"]');
  var box = document.getElementById('address-box');
  if (!radios.length || !box) return;
  function refresh() {
    var sel = document.querySelector('input[name="delivery_method"]:checked');
    box.style.display = sel && sel.value === 'delivery' ? 'block' : 'none';
  }
  radios.forEach(function (r) { r.addEventListener('change', refresh); });
  refresh();
});
