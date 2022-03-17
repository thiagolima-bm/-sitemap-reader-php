<?php

namespace Snowdog\SitemapReader\Service;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\WebsiteManager;

class SitemapManager
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;

    /**
     * SitemapManager constructor.
     * @param WebsiteManager $websiteManager
     * @param PageManager $pageManager
     */
    public function __construct(
        WebsiteManager $websiteManager,
        PageManager $pageManager
    ) {
        $this->websiteManager = $websiteManager;
        $this->pageManager = $pageManager;
    }

    /**
     * @param User $user
     * @param SitemapParser $parser
     * @return bool
     */
    public function execute(User $user, SitemapParser $parser)
    {
        if ($parser->getErrors()) {
            return false;
        }
        $website = $this->getWebsite($parser, $user);
        if (!$website) {
            $parser->addError("Invalid website");
            return false;
        }

        foreach ($parser->getPages() as $url) {
            $this->pageManager->create($website, $url);
        }
        return true;
    }

    /**
     * @param SitemapParser $parser
     * @param User $user
     * @return \Snowdog\DevTest\Model\Website
     */
    private function getWebsite(SitemapParser $parser, User $user)
    {
        $website = $this->websiteManager->geWebsiteByHostAndUser($parser->getWebsite(), $user->getUserId());
        if (!$website) {
            // it does not exist, let's try to create a new one
            $websiteId = $this->websiteManager->create(
                $user,
                $parser->getWebsite(),
                $parser->getWebsite()
            );
            $website = $this->websiteManager->getById($websiteId);
        }
        return $website;
    }
}