document.addEventListener("DOMContentLoaded", function() {
    var today = new Date().toISOString().split('T')[0];
    document.getElementsByName("transfer_date")[0].setAttribute('min', today);
});