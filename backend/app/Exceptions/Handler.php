<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Laravel\Lumen\Exceptions\Handler as LumenExceptionHandler;
use Throwable;

class Handler extends LumenExceptionHandler
{
    /**
     * Report or log an exception.
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        return parent::render($request, $e);
    }
}
