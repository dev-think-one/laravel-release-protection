<?php


namespace ReleaseProtection\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Mockery;
use ReleaseProtection\Http\Middleware\VerifyFirstPartyClientIp;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VerifyFirstPartyClientIpTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Config::set('release-protection.ip.ips.default', [ '123.4.5.6', '123.4.5.7' ]);
    }

    protected function prepareRequest($ip = null)
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('ip')->once()->andReturn($ip);

        return $request;
    }

    /** @test */
    public function ip_in_allowlist()
    {
        $middleware = new VerifyFirstPartyClientIp();

        $result = $middleware->handle($this->prepareRequest('123.4.5.7'), fn () => 'test-closure');

        $this->assertEquals('test-closure', $result);
    }

    /** @test */
    public function ip_not_in_allowlist()
    {
        $middleware = new VerifyFirstPartyClientIp();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('You are not allowed to visit this directory');
        $this->expectExceptionCode(0);
        $middleware->handle($this->prepareRequest('123.4.5.0'), fn () => 'test-closure');
    }

    /** @test */
    public function ip_not_in_allowlist_override_text()
    {
        Config::set('release-protection.ip.msg.restricted.default', 'My message');

        $middleware = new VerifyFirstPartyClientIp();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('My message');
        $this->expectExceptionCode(0);
        $middleware->handle($this->prepareRequest('123.4.5.0'), fn () => 'test-closure');
    }
}
