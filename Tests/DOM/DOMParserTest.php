<?php

declare(strict_types=1);

/*
 * This file is part of the ConnectHolland CookieConsentBundle package.
 * (c) Connect Holland.
 */

namespace ConnectHolland\CookieConsentBundle\Tests\DOM;

use ConnectHolland\CookieConsentBundle\DOM\DOMParser;
use PHPUnit\Framework\TestCase;

class DOMParserTest extends TestCase
{
    /**
     * @var DOMParser
     */
    private $domParser;

    public function setUp()
    {
        $this->domParser = new DOMParser();
    }

    /**
     * @dataProvider buildConsentDomDataProvider
     */
    public function testBuildConsentDom(string $html, string $contentToAppend, string $expected): void
    {
        $result = $this->domParser->appendToBody($html, $contentToAppend);

        $this->assertSame($expected, $result);
    }

    public function buildConsentDomDataProvider(): array
    {
        return [
            [
'<html><body><div></div></body></html>',
'<div>Cookie consent</div>',
'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
<html><body><div></div><div>Cookie consent</div></body></html>
',
            ],
        ];
    }
}
