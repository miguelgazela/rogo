<header>
    <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="{$BASE_URL}index.php"><i class="icon-comments-alt"></i> ROGO</a>
                <ul class="nav">
                    <li class="_questions"><a href="{$BASE_URL}index.php"><i class="icon-question-sign"></i> Questions</a></li>
                    <li class="_users"><a href="{$BASE_URL}pages/users/list.php?sort=reputation"><i class="icon-group"></i> Users</a></li>
                    <li class="_tags"><a href="{$BASE_URL}pages/tags/list.php?sort=popular"><i class="icon-tags"></i> Tags</a></li>

                    {if $s_username == ""}
                        <li class="_signin"><a href="{$BASE_URL}pages/auth/signin.php"><i class="icon-signin"></i> Log in</a></li>
                        <li class="_signup"><a href="{$BASE_URL}pages/auth/signup.php"><i class="icon-plus"></i> Sign up</a></li>
                    {else}
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-user"></i> {$s_username}
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{$BASE_URL}pages/questions/add.php"><i class="icon-comment"></i> Ask Question</a></li>
                                <li class="divider"></li>
                                <li><a href="{$BASE_URL}pages/users/view.php?id={$s_user_id}"><i class="icon-user"></i> Profile</a></li>
                                <li><a href="{$BASE_URL}pages/privmessages/list.php"><i class="icon-envelope-alt"></i> Messages</a></li>
                                <li><a href="{$BASE_URL}pages/users/view.php?id={$s_user_id}&sort=drafts"><i class="icon-pencil"></i> Drafts</a></li>
                                <li><a href="#"><i class="icon-flag"></i> Notifications</a></li>
                                <li class="divider"></li>
                                <li><a href="{$BASE_URL}actions/auth/logout.php"><i class="icon-signout"></i> Logout</a></li>
                            </ul>
                        </li>
                    {/if}
                    
                </ul>

                <form class="navbar-search pull-right">
                    <input type="text" class="search-query" placeholder="Search">
                </form>
            </div>
        </div>
    </div>
</header>