function initializeModal() {

    var modal = document.getElementById("myModal");

    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    if (document.getElementById('successMessage').innerHTML.trim() !== '') {
        modal.style.display = "block";
    }
}
