<?php
global $action;
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Charles Jackson</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
        <link href="css/main.css" rel="stylesheet">
        <link href="css/carousel.css" rel="stylesheet">
    </head>
    <body>

        <!-- navigation start -->
        <div class="navbar-wrapper">
            <div class="container">

                <div class="navbar navbar-inverse navbar-static-top" role="navigation">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#">Jackson</a>
                        </div>
                        <div class="navbar-collapse collapse">
                            <ul class="nav navbar-nav">
                                <li class="<?php if ($action == 'default') echo 'active'; ?>"><a href="index.php">Home</a></li>
                                <?php
// check to see if the user is logged in
if (isset($_SESSION['user']))
{
                                ?>
                                <li class="<?php if ($action == 'dashboard') echo 'active'; ?>"><a href="index.php?action=dashboard">Dashboard</a></li>
                                <?php
} else {
                                ?>
                                <li class="<?php if ($action == 'logout') echo 'active'; ?>"><a href="assignments.php">Assignments</a></li>
                                <?php
}
                                ?>

                                <!--
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
<ul class="dropdown-menu" role="menu">
<li><a href="#">Action</a></li>
<li><a href="#">Another action</a></li>
<li><a href="#">Something else here</a></li>
<li class="divider"></li>
<li class="dropdown-header">Nav header</li>
<li><a href="#">Separated link</a></li>
<li><a href="#">One more separated link</a></li>
</ul>
</li>
-->
                            </ul>
                            <ul class="nav navbar-nav pull-right">
                                <?php
// check to see if the user is logged in
if (isset($_SESSION['user']))
{
    // logged in
                                ?>
                                <li class="<?php if ($action == ' logout ') echo 'active'; ?> pull-right"><a href="index.php?action=logout">Logout</a></li>
                                <?php
} else {
    // not logged in
                                ?>
                                <li class="<?php if ($action == 'login') echo 'active'; ?> pull-right"><a href="index.php?action=login">Login</a></li>
                                <?php

}
                                ?>

                            </ul>
                        </ul>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- navigation start -->

    <!-- message engine -->
    <div class="messages">
        <!--
<div class="container">
<div class="alert alert-dismissable alert-error">
<button type="button" class="close" data-dismiss="alert">×</button>

Test error
</div>
<div class="alert alert-dismissable alert-warning">
<button type="button" class="close" data-dismiss="alert">×</button>
Test error
</div>
<div class="alert alert-dismissable alert-success">
<button type="button" class="close" data-dismiss="alert">×</button>
Test error
</div>
</div>
-->
    </div>