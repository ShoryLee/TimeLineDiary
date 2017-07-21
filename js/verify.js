$(function() {
    
    $('button').on('click', function() {
        if($("input[name='password']").val() !== $("input[name='password2']").val()) {
            return false;
        }
        
    });
});