<?php
$StopwatchHelper = new StopwatchHelper();
$StopwatchHelper->start();
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>WPCustomRepository</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap-united.min.css">
    <link rel="stylesheet" href="/assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/screen.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/">WPCustomRepository</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <?php if (AuthHelper::isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Plugins
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/plugin/base/list">Base Plugin List</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/plugin/base/add">Add Base Plugin</a>
                            <a class="dropdown-item" href="/plugin/version/add">Add Plugin Version</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Themes
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/theme/base/list">Theme List</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/theme/base/add">Add Theme</a>
                            <a class="dropdown-item" href="/theme/version/add">Add Theme Version</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            License
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/license/list">License List</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/license/add">Add License</a>
                        </div>
                    </li>
                <?php endif; ?>
                <!--<li class="nav-item dropdown">
                    <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Language
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/language?locale=de"><img src="/assets/images/country_flags/de.png" height="14">&nbsp; German</a>
                        <a class="dropdown-item" href="/language?locale=en"><img src="/assets/images/country_flags/en.png" height="14">&nbsp; English</a>
                    </div>
                </li>-->
                <?php if (AuthHelper::isLoggedIn() && AuthHelper::isAdmin($_SESSION['user'])): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="width: 250px;">
                        <a class="dropdown-item" href="/admin/dashboard">Dashboard</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/admin/settings">System Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/admin/user/list">User Overview</a>
                        <a class="dropdown-item" href="/admin/user/add">Add User</a>
                        <?php if ((bool)getenv('EMAIL_TRACKING_ENABLED') === true): ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/admin/tracking-mail/list">Tracking E-Mails</a>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cog"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="width: 200px;">
                        <?php if (AuthHelper::isLoggedIn()): ?>
                            <a class="dropdown-item" href="/user/settings">Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="/logout" data-tooltip="" data-placement="bottom" title="" data-original-title="Logged in as: <?AuthHelper::getUsername();?>">
                                Logout
                            </a>
                        <?php else: ?>
                            <a class="dropdown-item" href="/login"><i class="fa fa-sign-in"></i> Login</a>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>