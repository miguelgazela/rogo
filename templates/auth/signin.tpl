<!DOCTYPE html>
<html lang="en">
    {include file="../header.tpl" title="Rogo - Sign in"}

    <body class="_signin">

        {include file="../navbar.tpl"}

        <div class="container">
            <div class="hero-unit sign-up-in">
                <h1 class="sign-in-rogo">Log in to your account</h1>
                <p>Don't have an account? <a href="{$BASE_URL}pages/auth/signup.php">Sign up.</a>
                <form id="signin-form" class="form-horizontal" action="{$BASE_URL}actions/auth/login.php" method="POST">
                    {if $s_error.login == ""}
                    <div class="control-group inputUsername">
                    {else}
                    <div class="control-group error inputUsername">
                    {/if}
                        <label class="control-label" for="inputUsername">Username</label>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-user"></i></span>
                                <input type="text" id="inputUsername" onblur="return validateUsername()" value="{$s_values.username}" name="username" placeholder="username">
                            </div>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    {if $s_error.login == ""}
                    <div class="control-group inputPassword">
                    {else}
                    <div class="control-group error inputPassword">
                    {/if}
                        <label class="control-label" for="inputPassword">Password</label>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-unlock"></i></span>
                                <input type="password" id="inputPassword" name="password" onblur="return validatePassword()" placeholder="password" value="{$s_values.password}">
                                <span class="help-block">
                                    {if $s_error.login != ""}
                                    {$s_error.login}
                                    {/if}
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <button type="submit" form="signin-form" class="btn">Sign in</button>
            </div>
        </div>

        {include file="../footer.tpl"}
    
    <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        {include file="../common-js.tpl"}
        <script>
            $("#signin_form").submit(function() {
                return (validateUsername() && validatePassword());
            })
        </script>
    </body>
</html>​


