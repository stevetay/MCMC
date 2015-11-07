<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response 
	 * @return Json
	 */
	public function render($request, Exception $e)
	{
		//return parent::render($request, $e);
		
		if($e->getCode()) {

			if($e->getCode() > 100) {
				$error = $e->getCode();
			} else {
				$error = http_response_code();
			}

	    	$return = \Response::json([
	             "status"=> $e->getCode(),
 	             "message"=> $e->getMessage(),
	    	], $error );

	    	return $return;
		}

		if($this->isHttpException($e)) {

	        switch ($e->getStatusCode()) {
	            case 100: $text = 'Continue'; break;
	            case 101: $text = 'Switching Protocols'; break;
	            case 200: $text = 'OK'; break;
	            case 201: $text = 'Created'; break;
	            case 202: $text = 'Accepted'; break;
	            case 203: $text = 'Non-Authoritative Information'; break;
	            case 204: $text = 'No Content'; break;
	            case 205: $text = 'Reset Content'; break;
	            case 206: $text = 'Partial Content'; break;
	            case 300: $text = 'Multiple Choices'; break;
	            case 301: $text = 'Moved Permanently'; break;
	            case 302: $text = 'Moved Temporarily'; break;
	            case 303: $text = 'See Other'; break;
	            case 304: $text = 'Not Modified'; break;
	            case 305: $text = 'Use Proxy'; break;
	            case 400: $text = 'Bad Request'; break;
	            case 401: $text = 'Unauthorized'; break;
	            case 402: $text = 'Payment Required'; break;
	            case 403: $text = 'Forbidden'; break;
	            case 404: $text = 'Not Found'; break;
	            case 405: $text = 'Method Not Allowed'; break;
	            case 406: $text = 'Not Acceptable'; break;
	            case 407: $text = 'Proxy Authentication Required'; break;
	            case 408: $text = 'Request Time-out'; break;
	            case 409: $text = 'Conflict'; break;
	            case 410: $text = 'Gone'; break;
	            case 411: $text = 'Length Required'; break;
	            case 412: $text = 'Precondition Failed'; break;
	            case 413: $text = 'Request Entity Too Large'; break;
	            case 414: $text = 'Request-URI Too Large'; break;
	            case 415: $text = 'Unsupported Media Type'; break;
	            case 500: $text = 'Internal Server Error'; break;
	            case 501: $text = 'Not Implemented'; break;
	            case 502: $text = 'Bad Gateway'; break;
	            case 503: $text = 'Service Unavailable'; break;
	            case 504: $text = 'Gateway Time-out'; break;
	            case 505: $text = 'HTTP Version not supported'; break;
	            default:
	                trigger_error('Unknown http status code ' . $code, E_USER_ERROR); // exit('Unknown http status code "' . htmlentities($code) . '"');
	                return $prev_code;
	        }

	    	$return = \Response::json([
	             "status"=> $e->getStatusCode(),
	             "message" => $text,
	             //"statusMessage" => http_response_code($e->getMessage()),
 	             "errorMessage"=> $e->getMessage(),
	    	], $e->getStatusCode());

	    	return $return;
		} 

		$return = \Response::json([
	         "status"=> http_response_code(),
	         "message"=> $e->getMessage(),
		], http_response_code());

		return $return;	
		// /return parent::render($request, $e);
		
	
	}

}
