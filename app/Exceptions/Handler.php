<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    protected $dontReport = [
        //
    ];


    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];


    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getMessage(), 405);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return $this->errorResponse('Not Found!', $exception->getMessage(), 404);
        }
        if ($exception instanceof RelationNotFoundException) {
            return $this->errorResponse('Llamada a una relación indefinida', $exception->getMessage(), 500);
        }


        if ($exception instanceof ModelNotFoundException) {
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ninguna instancia de modelo '{$modelo}' con el id especificado", $exception->getMessage(), 404);
        }

        if ($exception instanceof QueryException) {
            $codigo = $exception->errorInfo[1];
            if ($codigo === 1062) {
                return $this->errorResponse('Entrada duplicada.', $exception->getMessage(), 409);
                // return $this->errorResponse('El nombre del taller ya se encuentra en uso.', 409);
            }
            if ($codigo == 1451) {
                return $this->errorResponse('No se puede eliminar de forma permanente el recurso porque esta relacionado con algun otro', $exception->getMessage(), 409);
            }
        }

        return parent::render($request, $exception);
    }

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
