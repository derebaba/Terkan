<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
	
	protected $proxies = '*';
	
	protected $headers = [
		Request::HEADER_FORWARDED    => null, // not set on AWS or Heroku
		Request::HEADER_CLIENT_IP    => 'X_FORWARDED_FOR',
		Request::HEADER_CLIENT_HOST  => null, // not set on AWS or Heroku
		Request::HEADER_CLIENT_PROTO => 'X_FORWARDED_PROTO',
		Request::HEADER_CLIENT_PORT  => 'X_FORWARDED_PORT',
	];
}
