<?php
/** @var \Tmvc\Framework\App\Config $config */
$config->addRoute('backend/index/index', "GET", Tmvc\Backend\Controller\Index\Index::class);