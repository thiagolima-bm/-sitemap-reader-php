<?php

use Snowdog\DevTest\Component\CommandRepository;
use Snowdog\DevTest\Component\Menu;
use Snowdog\DevTest\Component\RouteRepository;
use Snowdog\SitemapReader\Command\SitemapReaderCommand;
use Snowdog\SitemapReader\Menu\SitemapMenu;

CommandRepository::registerCommand('sitemap_reader [sitemapUrl] [userLogin]', SitemapReaderCommand::class);
Menu::register(SitemapMenu::class, 30);

RouteRepository::registerRoute('GET', '/sitemap', \Snowdog\SitemapReader\Controller\SitemapAction::class, 'execute');
RouteRepository::registerRoute('POST', '/sitemap', \Snowdog\SitemapReader\Controller\ImportSitemapAction::class, 'execute');
