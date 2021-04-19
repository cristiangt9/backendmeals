<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class FailduringCreate extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        Log::bug($this->getMessage());
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function render($request)
    // {
    //     return response(...);
    // }
}
