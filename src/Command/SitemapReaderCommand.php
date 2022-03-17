<?php

namespace Snowdog\SitemapReader\Command;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\SitemapReader\Service\SitemapManager;
use Snowdog\SitemapReader\Service\SitemapParser;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapReaderCommand
{
    /**
     * @var SitemapParser
     */
    private $sitemapParser;
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * SitemapReaderCommand constructor.
     * @param SitemapParser $sitemapParser
     */
    public function __construct(
        SitemapParser $sitemapParser,
        SitemapManager $sitemapManager,
        UserManager $userManager
    ) {
        $this->sitemapParser = $sitemapParser;
        $this->userManager = $userManager;
        $this->sitemapManager = $sitemapManager;
    }

    public function __invoke($sitemapUrl, $userLogin, OutputInterface $output)
    {
        $user = $this->userManager->getByLogin($userLogin);
        if (!$user) {
            $output->writeln("Please provide a valid user login, \"{$userLogin}\" is not a valid login");
            return;
        }
        $output->writeln("Trying to read data from: " . $sitemapUrl);
        if (filter_var($sitemapUrl, FILTER_VALIDATE_URL)) {
            $output->writeln("$sitemapUrl is a valid URL");
            $this->sitemapParser->parse($sitemapUrl);
            if (!$this->sitemapManager->execute($user, $this->sitemapParser)) {
                $output->writeln("Error trying to parse the sitemap");
                foreach ($this->sitemapParser->getErrors() as $error) {
                    $output->writeln("$error");
                }
            }
            $output->writeln("sitemap $sitemapUrl imported successfully!");
        } else {
            $output->writeln("$sitemapUrl is not a valid URL");
        }
    }
}
