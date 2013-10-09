<?php 
//Highlights a row on mouseover, reverts back on mouseout
//When row is clicked, user is transferred to the url specified by that link.
?>
<script type="text/javascript">
function highlight(row)
{
    row.className = "listTextHL";
}

function revert(row, oldClass)
{
    row.className = oldClass;
}
</script>

