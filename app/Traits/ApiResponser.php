<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $errors, $code)
    {
        return response()->json(['message' => $message, 'errors' => $errors, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $code);
        }

        if (request()->has('paginate'))
            $collection = $this->paginate($collection);
        else
            $collection = ["data" => $collection];
        return $this->successResponse($collection, $code);
    }

    protected function showOne(Model $instance, $code = 200)
    {
        return $this->successResponse(['data' => $instance], $code);
    }

    protected function showMessage($message, $content = null, $code = 200)
    {
        return $this->successResponse(['message' => $message, 'content' => $content, 'code' => $code], $code);
    }

    protected function paginate(Collection $collection)
    {
        $rules = [
            'perPage' => 'integer|min:2',
            'currentPage' => 'integer|min:1'
        ];

        Validator::validate(request()->all(), $rules);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if (request()->has('currentPage')) {
            $currentPage = (int) request()->currentPage;
        }

        $perPage = 15;
        if (request()->has('perPage')) {
            $perPage = (int) request()->perPage;
        }

        $results = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        // Para conservar los otros parametros de la url
        $paginated->appends(request()->all());

        return $paginated;
    }
    protected function delete($content, $message = 'Datos eliminados correctamente')
    {
        $remove = $content->delete();
        return response()->json(['message' => $message, 'remove' => $remove, 'content' => $content]);
    }
}
