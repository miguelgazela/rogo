<!DOCTYPE html>
<html lang="en">
    {include file="header.tpl" title="Rogo - Ups, you have to login"}

    <body>

        {include file="navbar.tpl"}

        <div class="container rogo-warning">
            <h3><i class="icon-info-sign"></i> Ups...</h3>
            <p>You have to <a href="{$BASE_URL}pages/auth/signin.php">log in</a> to get access</p>
        </div>

        {include file="footer.tpl"}
    
    <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        {include file="common-js.tpl"}
    </body>
</html>​


