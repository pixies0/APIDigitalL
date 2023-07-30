<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;
use Exception;
use App\Exceptions\AppError;


class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return response()->json(['ok' => false, 'codigo' => 'ERRO_VALIDACAO', 'message' => $e->errors()], 422);
        } else {
            $erro = $e->getMessage() . "\n\n -------------- \n\n" . $request->__toString();

            $httpCode = 500;
            $shouldLog = true;

            if ($e instanceof AppError) {
                $httpCode = $e->getHttpCode();
                $shouldLog =  $e->getShouldLog();
            } else if ($e instanceof Exception) {
                $httpCode = $e->getMessage() == 'Unauthenticated.' ? 401 : 500;
            }

            if ($httpCode != 401 && $shouldLog) {
                $report_error = Erro('Erro do sistema', $erro);
            } else {
                $report_error = 'NAO_AUTORIZADO';
            }


            $return_format = ['ok' => false, 'codigo' => $report_error, 'message' => $e->getMessage()];

            if (env('APP_DEBUG')) {
                $return_format['trace'] = $e->getTrace();
            }

            return response()->json($return_format, $httpCode);
        }

        return parent::render($request, $e);
    }
}
