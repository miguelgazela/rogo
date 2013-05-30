<!DOCTYPE html>
<html lang="en">
   
    {include file="../header.tpl" title="Rogo - Sign up"}

    <body class="_signup">

        {include file="../navbar.tpl"}

        <div class="container">
            <div class="hero-unit sign-up-in">
                <h1 class="sign-up-rogo">Sign up to Rogo</h1>
                <p>Come join the Rogo community! Let's set up your brand new account. Already have one? <a href="{$BASE_URL}pages/auth/signin.php">Sign in.</a>
                <form id="signup_form" class="form-horizontal" action="{$BASE_URL}actions/users/add_action.php" method="post">
                    {if $s_error.username == ""}
                    <div class="control-group inputUsername">
                    {else}
                    <div class="control-group error inputUsername">
                    {/if}
                        <label class="control-label" for="inputUsername">Username</label>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-user"></i></span>
                                <input type="text" id="inputUsername" name="username" placeholder="Username" onblur="return validateUsername()" value="{$s_values.username}">
                            </div>
                            <span class="help-block">
                                {if $s_error.username == "no_username" || $s_error.username == "invalid"}
                                Invalid username. Between 4 and 20 alfanumeric chars, underscores and points
                                {elseif $s_error.username == "username_taken"}
                                This username is already taken
                                {/if}
                            </span>
                        </div>
                    </div>
                    {if $s_error.email == ""}
                    <div class="control-group inputEmail">
                    {else}
                    <div class="control-group error inputEmail">
                    {/if}
                        <label class="control-label" for="inputEmail">Email</label>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-envelope"></i></span>
                                <input type="text" id="inputEmail" name="email" placeholder="Email" onblur="return validateEmail()" value="{$s_values.email}">
                            </div>
                            <span class="help-block">
                                {if $s_error.email == "no_email" || $s_error.email == "invalid"}
                                Invalid email
                                {elseif $s_error.email == "email_taken"}
                                This email is already registered in our website
                                {/if}
                            </span>
                        </div>
                    </div>
                    {if $s_error.password == ""}
                    <div class="control-group inputPassword">
                    {else}
                    <div class="control-group error inputPassword">
                    {/if}
                        <label class="control-label" for="inputPassword">Password</label>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-unlock"></i></span>
                                <input type="password" id="inputPassword" name="pass1" placeholder="Password" onblur="return validatePassword()">
                            </div>
                            <span class="help-block">
                                {if $s_error.password == "invalid" || $s_error.password == "no_password"}
                                Invalid password. Between 6 and 30 alfanumeric chars and !@#$%^&*_
                                {/if}
                            </span>
                        </div>
                    </div>
                    {if $s_error.password == ""}
                    <div class="control-group confirmPassword">
                    {else}
                    <div class="control-group error confirmPassword">
                    {/if}
                        <label class="control-label" for="inputPasswordConfirmation">Confirm password</label>
                        <div class="controls">
                            <div class="input-prepend">
                                <span class="add-on"><i class="icon-lock"></i></span>
                                <input type="password" id="inputPasswordConfirmation" name="pass2" placeholder="Password" onblur="return confirmPassword()">
                            </div>
                            <span class="help-block">
                                {if $s_error.password == "no_match" || $s_error.password == "no_confirmation_password"}
                                Passwords don't match!
                                {/if}
                            </span>
                        </div>
                    </div>
                </form>
                <button type="submit" class="btn" form="signup_form">Join Rogo</button>
            </div>
        </div>

        {include file="../footer.tpl"}
    
    <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        {include file="../common-js.tpl"}
        <script>
            $("#signup_form").submit(function(event) {
                event.preventDefault();
                return (validateUsername() && validateEmail() && validatePassword() && confirmPassword());
            })
        </script>
    </body>
</html>​


