<!DOCTYPE html>
<html lang="en">
    {include file="header.tpl" title="Rogo - Ups, you found an error"}

    <body>

        {include file="navbar.tpl"}

        <div class="container rogo-warning">
            <h3><i class="icon-info-sign"></i> Ups...</h3>
            <p>{$warning_msg}</p>
        </div>

        {include file="footer.tpl"}
    
    <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        {include file="common-js.tpl"}
    </body>
</html>​


