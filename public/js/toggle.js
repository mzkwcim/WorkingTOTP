$(document).ready(function() {
    $(".transaction-row").click(function() {
        $(this).next(".transaction-details").slideToggle();
    });
});