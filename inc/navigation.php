<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">HH</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
           <ul class="nav navbar-nav navbar-right">
               <? if(!$USER): ?>
                   <li><a href="login.php">Вход</a></li>
                   <li><a href="registration.php">Регистрация</a></li>
               <? else: ?>
                   <li class="dropdown">
                       <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$USER['fio']?> <span class="caret"></span></a>
                       <ul class="dropdown-menu">
                           <li><a href="profile.php">Профиль</a></li>
                           <li><a href="products.php">Товары</a></li>
                           <li><a href="admin.php">Админ</a></li>
                           <li role="separator" class="divider"></li>
                           <li><a href="logout.php">Выход</a></li>
                       </ul>
                   </li>
               <? endif; ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>