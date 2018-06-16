<?php
/* @var \Tmvc\Framework\App\Config $config */
$config->addRoute('index', "GET", \Tmvc\Cms\Controller\Index\Index::class);
$config->addRoute('noroute', "GET", \Tmvc\Cms\Controller\Noroute\Index::class);
$config->addRouter(\Tmvc\Cms\Controller\Router::class);