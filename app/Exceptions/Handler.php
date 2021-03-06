<?php

namespace App\Exceptions;

use App\AdminApi\AdminOnlyException;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        AdminOnlyException::class
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
        parent::report($e);
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
        if ($e instanceof AdminOnlyException)
        {
            return \Response::json(['error' => 'Forbidden', 'message' => 'Administrator action only'], 403);
        }
        if ($e instanceof ModelNotFoundException && $request->route()->getPrefix() == '/' . config('admin.prefix'))
        {
            return \Response::json(['error' => 'NotFound', 'message' => 'Entity does not existed'], 404);
        }
        return parent::render($request, $e);
    }
}
