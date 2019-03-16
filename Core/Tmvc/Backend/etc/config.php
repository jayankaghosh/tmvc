<?php
/** @var \Tmvc\Framework\App\Config $config */
$config->addRoute('backend/index/index', "GET", Tmvc\Backend\Controller\Index\Index::class);
$config->addRoute('backend/login/index', "GET", Tmvc\Backend\Controller\Login\Index::class);
$config->addRoute('backend/logout/index', "GET", Tmvc\Backend\Controller\Logout\Index::class);
$config->addRoute('backend/login/post', "POST", Tmvc\Backend\Controller\Login\Post::class);
$config->addRoute('backend/service/index', "GET", \Tmvc\Backend\Controller\Service\Index::class);
$config->addRoute('backend/service/index', "POST", \Tmvc\Backend\Controller\Service\Index::class);
$config->addObserver('backend/index/index_predispatch', \Tmvc\Backend\Observer\DashboardLoadBefore::class);