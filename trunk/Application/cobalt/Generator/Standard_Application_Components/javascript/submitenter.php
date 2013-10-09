<?php 
//Makes a form submit when enter is pressed on the field that calls this function
?>
<script type="text/javascript">
function submitenter(myfield,e)
{
    var keycode;
    if (window.event) keycode = window.event.keyCode;
    else if (e) keycode = e.which;
    else return true;

    if (keycode == 13)
    {
       myfield.form.submit();
       return false;
    }
    else
       return true;
}
</script>
