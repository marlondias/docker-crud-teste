<?php

namespace App\Http\Controllers\Traits;

use Exception;
use Illuminate\Support\Collection;
use stdClass;

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

}