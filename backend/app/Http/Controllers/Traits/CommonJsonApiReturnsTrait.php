<?php

namespace App\Http\Controllers\Traits;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use stdClass;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait CommonJsonApiReturnsTrait
{

    public function getSuccessContent($data, object $metadata = null)
    {
        if (! is_array($data) && ! $data instanceof Collection) {
            throw new Exception('Invalid type for argument "data".');
        }

        return (object) [
            'data' => ($data instanceof Collection) ? $data->toArray() : $data,
            'metadata' => $metadata
        ];
    }

    public function getErrorContent(string $message, Exception $exception = null)
    {
        $error = new stdClass;
        $error->message = $message;
        if (is_object($exception)) {
            $error->exception = $exception->getMessage();
        }
        return (object) ['error' => $error];
    }

    public function getJsonResponseFromException(string $baseMessage, \Exception $exception)
    {
        $statusCode = 500;
        $errorCause = 'Falha durante processamento.';
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            if ($statusCode < 500) {
                $errorCause = 'Entrada inválida na requisição.';
            }
        } elseif ($exception instanceof ModelNotFoundException) {
            $statusCode = 404;
            $errorCause = 'Registro não encontrado.';
        } elseif ($exception instanceof ValidationException) {
            $statusCode = 400;
            $errorCause = 'Erro ao validar query.';
        }
        $errorObj = $this->getErrorContent("{$baseMessage}. {$errorCause}", $exception);
        return response()->json($errorObj, $statusCode);
    }

}
