<?php 

namespace App\Http\Controllers\API\Contracts;

use App\Http\Controllers\Controller;

class APIController extends Controller
{
    protected $statusCode;

    public function respondSuccess(string $message = '', array $data = null)
    {
        $this->setStatusCode(200);

        return $this->respond($message, true, $data);
    }

    public function respondCreated(string $message = '', array $data = null)
    {
        $this->setStatusCode(201);

        return $this->respond($message, true, $data);
    }

    public function respondNotFound(string $message = '')
    {
        $this->setStatusCode(404);

        return $this->respond($message, false);
    }

    public function respondForbidden(string $message = '')
    {
        $this->setStatusCode(403);

        return $this->respond($message, false);
    }

    public function respondInvalidValidation(string $message = '')
    {
        $this->setStatusCode(422);

        return $this->respond($message, false);
    }

    public function respondInternalError(string $message = '')
    {
        $this->setStatusCode(500);

        return $this->respond($message, false);
    }

    private function respond(string $message = '', bool $isSuccess = false, array $data = null)
    {
        return response()->json([
            'success' => $isSuccess,
            'message' => $message,
            'data' => $data
        ])->setStatusCode($this->getStatusCode());
    }

    private function setStatusCode(int $statusCode = 200)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    private function getStatusCode()
    {
        return $this->statusCode;
    }
}