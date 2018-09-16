<?php
/** @var \Tmvc\Framework\App\Config $config */

$config->addRoute('backend/cache/clean', 'POST', \Tmvc\Framework\Cache\Controller\Backend\Cache\Clean::class);
