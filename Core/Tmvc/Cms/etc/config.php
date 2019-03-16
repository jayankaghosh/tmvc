<?php
/* @var \Tmvc\Framework\App\Config $config */
$config->addRoute('index', "GET", \Tmvc\Cms\Controller\Index\Index::class);
$config->addRoute('noroute', "GET", \Tmvc\Cms\Controller\Noroute\Index::class);
$config->addRouter(\Tmvc\Cms\Controller\Router::class);

$config->addObserver('view_render_after', \Tmvc\Cms\Observer\ViewRenderAfter::class);

/** @var \Tmvc\Backend\Service\ServicePool $servicePool */
$servicePool = $config->getInstance(\Tmvc\Backend\Service\ServicePool::class);
$servicePool->addService('cms_page_save', \Tmvc\Cms\Model\Backend\Service\CmsPageSave::class);
$servicePool->addService('cms_page_delete', \Tmvc\Cms\Model\Backend\Service\CmsPageDelete::class);