<header>
    <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="#"><i class="icon-comments-alt"></i> ROGO</a>
                <ul class="nav">
                    <li class="_questions"><a href="{$BASE_URL}index.php"><i class="icon-question-sign"></i> Questions</a></li>
                    <li class="_users"><a href="{$BASE_URL}pages/users/list.php?sort=reputation"><i class="icon-group"></i> Users</a></li>
                    <li class="_tags"><a href=""><i class="icon-tags"></i> Tags</a></li>

                    {if $s_username == ""}
                        <li class="_signin"><a href="{$BASE_URL}pages/auth/signin.php"><i class="icon-signin"></i> Log in</a></li>
                        <li class="_signup"><a href="{$BASE_URL}pages/auth/signup.php"><i class="icon-plus"></i> Sign up</a></li>
                    {else}
                        <li><a href="{$BASE_URL}actions/auth/logout.php"><i class="icon-signout"></i> Sign out</a></li>
                    {/if}
                    
                </ul>

                <form class="navbar-search pull-right">
                    <input type="text" class="search-query" placeholder="Search">
                </form>
            </div>
        </div>
    </div>
</header>