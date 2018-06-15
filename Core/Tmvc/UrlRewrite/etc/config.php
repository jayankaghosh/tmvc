<?php
/* @var \Tmvc\Framework\App\Config $config */

/* Add router to pool to handle URL Rewrites */
$config->addRouter(\Tmvc\UrlRewrite\Controller\Router::class);