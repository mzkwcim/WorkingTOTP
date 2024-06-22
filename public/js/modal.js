document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("close")[0];

    window.showModal = function() {
        modal.style.display = "block";
    }

    window.closeModal = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
});
