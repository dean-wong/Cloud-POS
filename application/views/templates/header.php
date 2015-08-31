<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title><?= $title ?></title>
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <!-- <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- 可选的Bootstrap主题文件（一般不用引入） -->
    <!-- <link href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" rel="stylesheet"> -->
    <link href="<?= base_url('assets/css/bootstrap-theme.min.css') ?>" rel="stylesheet">

    <!-- <link href="//cdn.bootcss.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet"> -->
    <link href="<?= base_url('assets/css/font-awesome.min.css') ?>" rel="stylesheet">

    <!-- JQuery UI -->
    <link href="<?= base_url('assets/css/custom-theme/jquery-ui-1.10.0.custom.css') ?>" rel="stylesheet">

    <!-- third-party -->
    <link rel="stylesheet" href="<?= base_url('assets/css/buttons.css') ?>">
<!--    <link rel="stylesheet" href="--><?//= base_url('assets/css/icheck-skins/all.css') ?><!--">-->

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="<?= base_url('assets/css/patch.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/cover.css') ?>">

</head>

<body>
<nav class="navbar navbar-fixed-top navbar-inverse">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= site_url('') ?>"><span class="glyphicon glyphicon-cloud" aria-hidden="true"></span> Cloud
                POS</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="<?= $title == 'Overview' ? 'active' : 'inactive' ?>"><a
                        href="<?= site_url('/pos/overview') ?>">
                        <span class="glyphicon glyphicon-th" aria-hidden="true"></span> 餐桌</a>
                </li>
                <li class="<?= $title == 'Order' ? 'active' : 'inactive' ?>">
                    <a href=""><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span> 点菜</a>
                </li>
                <li class="<?= $title == 'Cashier' ? 'active' : 'inactive' ?>">
                    <a href=""><span class="glyphicon glyphicon-yen" aria-hidden="true"></span> 结账</a>
                </li>
            </ul>

            <!-- Help and Settings -->
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"> <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> 帮助</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">设置 <span class="caret"></span></a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                        <li><a href="#">营业记录</a></li>
                        <li><a href="#">运营分析</a></li>
                        <li><a href="#">系统设置</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">退出系统</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>
