<?php

namespace League\Uri\Test\Services;

use League\Uri;
use PHPUnit_Framework_TestCase;

/**
 * @group formatter
 */
class FormatterTest extends PHPUnit_Framework_TestCase
{
    private $url;

    public function setUp()
    {
        $this->url = Uri\Uri::createFromComponents(
            new Uri\Schemes\Registry(),
            Uri\Uri::parse('http://login:pass@gwóźdź.pl:443/test/query.php?kingkong=toto&foo=bar+baz#doc3')
        );
    }

    public function testFormatHostAscii()
    {
        $formatter = new Uri\Formatter();
        $this->assertSame(Uri\Formatter::HOST_AS_UNICODE, $formatter->getHostEncoding());
        $formatter->setHostEncoding(Uri\Formatter::HOST_AS_ASCII);
        $this->assertSame(Uri\Formatter::HOST_AS_ASCII, $formatter->getHostEncoding());
        $this->assertSame('xn--gwd-hna98db.pl', $formatter->format($this->url->host));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidHostEncoding()
    {
        (new Uri\Formatter())->setHostEncoding('toto');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidQueryEncoding()
    {
        (new Uri\Formatter())->setQueryEncoding('toto');
    }

    public function testFormatWithSimpleString()
    {
        $url       = 'https://login:pass@gwóźdź.pl:443/test/query.php?kingkong=toto&foo=bar+baz#doc3';
        $expected  = 'https://login:pass@xn--gwd-hna98db.pl/test/query.php?kingkong=toto&amp;foo=bar%20baz#doc3';

        $url = Uri\Uri::createFromComponents(new Uri\Schemes\Registry(), Uri\Uri::parse($url));

        $formatter = new Uri\Formatter();
        $formatter->setQuerySeparator('&amp;');
        $formatter->setHostEncoding(Uri\Formatter::HOST_AS_ASCII);
        $this->assertSame($expected, $formatter->format($url));
    }

    public function testFormatComponent()
    {
        $scheme = new Uri\Scheme('ftp');
        $this->assertSame($scheme->__toString(), (new Uri\Formatter())->format($scheme));
    }

    public function testFormatHostUnicode()
    {
        $formatter = new Uri\Formatter();
        $formatter->setHostEncoding(Uri\Formatter::HOST_AS_UNICODE);
        $this->assertSame('gwóźdź.pl', $formatter->format($this->url->host));
    }

    public function testFormatQueryRFC1738()
    {
        $formatter = new Uri\Formatter();
        $this->assertSame(PHP_QUERY_RFC3986, $formatter->getQueryEncoding());
        $formatter->setQueryEncoding(PHP_QUERY_RFC1738);
        $this->assertSame(PHP_QUERY_RFC1738, $formatter->getQueryEncoding());
        $this->assertSame('kingkong=toto&foo=bar+baz', $formatter->format($this->url->query));
    }

    public function testFormatQueryRFC3986()
    {
        $formatter = new Uri\Formatter();
        $formatter->setQueryEncoding(PHP_QUERY_RFC3986);
        $this->assertSame('kingkong=toto&foo=bar%20baz', $formatter->format($this->url->query));
    }

    public function testFormatQueryWithSeparator()
    {
        $formatter = new Uri\Formatter();
        $this->assertSame('&', $formatter->getQuerySeparator());
        $formatter->setQuerySeparator('&amp;');
        $this->assertSame('&amp;', $formatter->getQuerySeparator());
        $this->assertSame('kingkong=toto&amp;foo=bar%20baz', $formatter->format($this->url->query));
    }

    public function testFormatURL()
    {
        $formatter = new Uri\Formatter();
        $formatter->setQuerySeparator('&amp;');
        $formatter->setHostEncoding(Uri\Formatter::HOST_AS_ASCII);
        $expected = 'http://login:pass@xn--gwd-hna98db.pl:443/test/query.php?kingkong=toto&amp;foo=bar%20baz#doc3';
        $this->assertSame($expected, $formatter->format($this->url));
    }

    public function testFormatWithoutAuthority()
    {
        $formatter = new Uri\Formatter();
        $formatter->setQuerySeparator('&amp;');
        $formatter->setHostEncoding(Uri\Formatter::HOST_AS_ASCII);
        $expected = '/test/query.php?kingkong=toto&amp;foo=bar%20baz#doc3';
        $url = $this->url->withScheme('')->withHost('')->withPort(null)->withUserInfo('');
        $this->assertSame($expected, $formatter->format($url));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFormatterFailed()
    {
        (new Uri\Formatter())->format('http://www.example.com');
    }
}
