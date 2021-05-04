$(document).ready(function() {
    $(".product-remove-wrapper").click(function() {
        alert("clicked");
        $(".product-img-wrapper").find('img')
            .css({'transform': 'scale(0)'})
            .promise().done(function() {
                $("#row-1").slideUp("slow", function() {
                    // Animation complete.
            });
        });
    });
});