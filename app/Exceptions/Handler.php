<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{

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
     */
    public function render($request, Exception $e)
    {
        if ($this->isHttpException($e)) {
            $statusCode = $e->getStatusCode();
            if ($statusCode === 404) {
                if ($request->is('admin/*')) {
                    return response()->make(view("admin.missing",[]), 404);
                } elseif ($request->is('site/*')) {
                    return response()->make(view("site.missing",[]), 404);
                } else {
                    $path = explode('/', $request->path());
                    return response()->make(view("site.errors.404",['q'=>end($path)]), 404);
                }
            }
            return response()->make(view("site.errors.{$statusCode}",['exception'=>$e]), $statusCode);
        }

        return parent::render($request, $e);
    }
}
