<?php
/* @var \Tmvc\Framework\App\Config $config */

/** @var \Tmvc\Backend\Service\ServicePool $servicePool */
$servicePool = $config->getInstance(\Tmvc\Backend\Service\ServicePool::class);
$servicePool->addService('tmvc_cache_clean', \Tmvc\Framework\Cache\Model\Backend\Service\CacheClean::class);