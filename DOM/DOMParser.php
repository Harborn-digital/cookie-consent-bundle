<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\DOM;

use Wa72\HtmlPageDom\HtmlPageCrawler;

class DOMParser
{
    /**
     * Append given content to body.
     */
    public function appendToBody(string $body, string $contentToAppend): string
    {
        $crawler = new HtmlPageCrawler($body);
        $crawler->filter('body')->append($contentToAppend);

        return $crawler->saveHTML();
    }
}
