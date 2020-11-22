<?php


namespace App\Responses;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;


class ErrorResponse extends JsonResponse
{
    /**
     * ErrorResponse constructor.
     * @param \Exception $exception
     * @param array $info Informacao adicional sobre o erro
     * @param int $status Status de resposta
     */
    public function __construct(Exception $exception, $info = [], $status = JsonResponse::HTTP_INTERNAL_SERVER_ERROR)
    {
        $data = [
            'error' => [
                'name' => get_class($exception),
                'description' => $exception->getMessage()
            ]
        ];

        foreach ($info as $item => $value) {
            $data[$item] = $value;
        }

        if (config('app.debug')) {
            $data['error']['file'] = $exception->getFile();
            $data['error']['line'] = $exception->getLine();
            $data['error']['stack'] = $exception->getTraceAsString();
        }

        if ($exception instanceof ValidationException) {
            $data['error']['description'] = $exception->validator->errors();
            $status = JsonResponse::HTTP_BAD_REQUEST;
        }

        parent::__construct($data, $status);
    }
}
