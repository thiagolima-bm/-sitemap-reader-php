<?php

namespace Snowdog\SitemapReader\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\SitemapReader\Service\SitemapManager;
use Snowdog\SitemapReader\Service\SitemapParser;

class ImportSitemapAction
{
    private $parser;
    private $sitemapManager;
    private $userManager;

    public function __construct(
        SitemapParser $parser,
        SitemapManager $sitemapManager,
        UserManager $userManager
    ) {
        $this->parser = $parser;
        $this->sitemapManager = $sitemapManager;
        $this->userManager = $userManager;
    }

    public function execute()
    {
        $sitemapUrl = $_POST['sitemap'];
        if (filter_var($sitemapUrl, FILTER_VALIDATE_URL)) {
            if (isset($_SESSION['login'])) {
                $user = $this->userManager->getByLogin($_SESSION['login']);
                $this->parser->parse($sitemapUrl);
                if ($this->sitemapManager->execute($user, $this->parser)) {
                    $_SESSION['flash'] = 'Sitemap imported successfully!';
                } else {
                    $_SESSION['flash'] = 'The importation has failed. Errors: ' . implode(', ', $this->parser->getErrors());
                }
            }
        } else {
            $_SESSION['flash'] = 'Please type a valid URL!';
        }

        header('Location: /sitemap');
    }
}
