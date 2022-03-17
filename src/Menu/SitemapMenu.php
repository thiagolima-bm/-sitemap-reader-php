<?php

namespace Snowdog\SitemapReader\Menu;

use Snowdog\DevTest\Menu\AbstractMenu;

class SitemapMenu extends AbstractMenu
{

    public function isActive()
    {
        return $_SERVER['REQUEST_URI'] == '/sitemap';
    }

    public function getHref()
    {
        return '/sitemap';
    }

    public function getLabel()
    {
        return 'Import Sitemap';
    }
}