<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 50px;
            padding-bottom: 20px;
        }
    </style>
    <link rel="stylesheet" href="/assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">

    <script src="/assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">WPCustomRepository</a>
        </div>
        
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav pull-right">
                <li><a href="#">Home</a></li>
                <?php if (AuthHelper::isLoggedIn() && AuthHelper::isAdmin($_SESSION['user'])) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown03">
                            <ul class="nav">
                                <li><a class="dropdown-item" href="/admin/dashboard">Dashboard</a></li>
                                <li class="nav-divider"></li>
                                <li><a class="dropdown-item" href="/admin/user/list">User List</a></li>
                                <li><a class="dropdown-item" href="/admin/user/add">Add User</a></li>
                                <?php if (getenv('EMAIL_TRACKING_ENABLED') === true) { ?>
                                    <li class="nav-divider"></li>
                                    <li><a class="dropdown-item" href="/admin/tracking_mail/list">Tracking Mail List</a></li>
                                    
                                <?php } ?>
                                
                            </ul>
                        </div>
                    </li>
                    
                <?php } ?>
                <li><a href="/plugin/list">Plugins</a></li>
                <?php if (!AuthHelper::isLoggedIn()) { ?> <li><a href="/login">Login</a></li> <?php } ?>
                
                
                <?php if (AuthHelper::isLoggedIn()) { ?> <li><a title="Logged in as <?php echo AuthHelper::getUsername(); ?>" href="/logout">Logout</a></li> <?php } ?>
            </ul>
        </div><!--/.navbar-collapse -->
        
    </div>
</nav>

