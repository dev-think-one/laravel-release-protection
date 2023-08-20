<?php


namespace ReleaseProtection\Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Mockery;
use ReleaseProtection\Http\Middleware\TestersEmailMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TestersEmailTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Config::set('release-protection.email.emails.default', [ 'my@email.com', 'other@email.com' ]);
    }

    protected function prepareUserToReturn($email = null)
    {
        $user        = new \stdClass();
        $user->email = $email;
        Auth::shouldReceive('guard')->once()->andReturnSelf();
        Auth::shouldReceive('user')->once()->andReturn($user);
    }


    /** @test */
    public function email_in_allowlist()
    {
        $this->prepareUserToReturn('other@email.com');
        $middleware = new TestersEmailMiddleware();

        $result = $middleware->handle(Mockery::mock(Request::class), fn () => 'test-closure');

        $this->assertEquals('test-closure', $result);
    }

    /** @test */
    public function email_not_in_allowlist()
    {
        $this->prepareUserToReturn('restricted@email.com');
        $middleware = new TestersEmailMiddleware();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('You are not allowed to visit this directory');
        $this->expectExceptionCode(0);
        $middleware->handle(Mockery::mock(Request::class), fn () => 'test-closure');
    }


    /** @test */
    public function no_user()
    {
        $middleware = new TestersEmailMiddleware();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Restricted area');
        $this->expectExceptionCode(0);
        $middleware->handle(Mockery::mock(Request::class), fn () => 'test-closure');
    }

    /** @test */
    public function email_not_in_allowlist_override_text()
    {
        Config::set('release-protection.email.msg.restricted.default', 'My message');

        $this->prepareUserToReturn('restricted@email.com');
        $middleware = new TestersEmailMiddleware();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('My message');
        $this->expectExceptionCode(0);
        $middleware->handle(Mockery::mock(Request::class), fn () => 'test-closure');
    }


    /** @test */
    public function no_user_override_text()
    {
        Config::set('release-protection.email.msg.not_logged.default', 'My message not_logged');

        $middleware = new TestersEmailMiddleware();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('My message not_logged');
        $this->expectExceptionCode(0);
        $middleware->handle(Mockery::mock(Request::class), fn () => 'test-closure');
    }


    /** @test */
    public function restrict_using_guard()
    {
        Config::set('release-protection.email.msg.restricted.default', 'My message guard');
        Config::set('release-protection.email.emails.my_guard', ['some@email.com']);

        $this->prepareUserToReturn('my@email.com');
        $middleware = new TestersEmailMiddleware();

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('My message guard');
        $this->expectExceptionCode(0);
        $middleware->handle(Mockery::mock(Request::class), fn () => 'test-closure', 'my_guard');
    }


    /** @test */
    public function pass_using_guard()
    {
        Config::set('release-protection.email.emails.my_guard', ['some@email.com']);

        $this->prepareUserToReturn('some@email.com');
        $middleware = new TestersEmailMiddleware();

        $result = $middleware->handle(Mockery::mock(Request::class), fn () => 'test-closure', 'my_guard');

        $this->assertEquals('test-closure', $result);
    }
}
