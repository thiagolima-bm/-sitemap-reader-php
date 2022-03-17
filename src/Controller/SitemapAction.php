<?php

namespace Snowdog\SitemapReader\Controller;

class SitemapAction
{
    public function execute() {

        include __DIR__ . '/../view/sitemap.phtml';
    }
}
