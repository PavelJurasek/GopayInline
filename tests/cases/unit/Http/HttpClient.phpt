<?php

/**
 * Test: Http\HttpClient
 */

use Contributte\GopayInline\Exception\HttpException;
use Contributte\GopayInline\Http\HttpClient;
use Contributte\GopayInline\Http\Io;
use Contributte\GopayInline\Http\Request;
use Contributte\GopayInline\Http\Response;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

// FALSE response
test(function () {
	$io = Mockery::mock(Io::class);
	$io->shouldReceive('call')->andReturn(FALSE);
	$http = new HttpClient();
	$http->setIo($io);

	Assert::throws(function () use ($http) {
		$http->doRequest(new Request());
	}, HttpException::class);
});

// Error response
test(function () {
	$error = (object) ['error_code' => 500, 'scope' => 'S', 'field' => 'F', 'message' => 'M'];
	$io = Mockery::mock(Io::class);
	$io->shouldReceive('call')->andReturnUsing(function () use ($error) {
		$r = new Response();
		$r->setData(['errors' => [$error]]);

		return $r;
	});
	$http = new HttpClient();
	$http->setIo($io);

	Assert::throws(function () use ($http, $error) {
		$http->doRequest(new Request());
	}, HttpException::class, HttpException::format($error));
});

// Success response
test(function () {
	$data = ['a' => 'b'];
	$io = Mockery::mock(Io::class);
	$io->shouldReceive('call')->andReturnUsing(function () use ($data) {
		$r = new Response();
		$r->setData($data);

		return $r;
	});
	$http = new HttpClient();
	$http->setIo($io);

	Assert::same($data, $http->doRequest(new Request())->data);
});
