<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Cache;
use Illuminate\Validation\ValidationException;
use Validator;
use Response;

trait ApiResponser
{
    /**
     * @param $data
     * @param int $code
     * @return Response
     */
    private function successResponse($data, $code = 200)
    {
        return response()->json($data, $code);
    }

    /**
     * @param $message
     * @param int $code
     * @return Response
     */
    protected function errorResponse($message, $code = 400)
    {
        return response()->json(['error' => $message], $code);
    }

    /**
     * @param Collection $collection
     * @param int $code
     * @return Response
     * @throws ValidationException
     */
    protected function showAll(Collection $collection, $code = 200)
    {
        if (!$collection->isEmpty()) {
            $transformer = $collection->first()->transformer;

            $collection = $this->filterData($collection, $transformer);
            $collection = $this->sortData($collection, $transformer);
            $collection = $this->paginate($collection);
            $collection = $this->transformData($collection, $transformer);
            $collection = $this->cacheResponse($collection);
        } else {
            $collection = ['data' => $collection];
        }

        return $this->successResponse($collection, $code);
    }

    /**
     * @param Model $instance
     * @param int $code
     * @return Response
     */
    protected function showOne(Model $instance, $code = 200)
    {
        $transformer = $instance->transformer;
        $instance = $this->transformData($instance, $transformer);

        return $this->successResponse($instance, $code);
    }

    /**
     * @param $message
     * @param int $code
     * @return Response
     */
    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    /**
     * @param $data
     * @param $transformer
     * @return array
     */
    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }

    /**
     * @param Collection $collection
     * @param $transformer
     * @return Collection|mixed
     */
    public function sortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by')) {
            $attribute = $transformer::originalAttribute(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }

        return $collection;
    }

    /**
     * @param Collection $collection
     * @param $transformer
     * @return Collection
     */
    protected function filterData(Collection $collection, $transformer)
    {
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalAttribute($query);

            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }

        return $collection;
    }

    /**
     * @param Collection $collection
     * @return LengthAwarePaginator
     * @throws ValidationException
     */
    protected function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        if (request()->has('per_page')) {
            $perPage = (int)request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }

    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParams = request()->query();

        ksort($queryParams);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 15 / 60, function () use ($data) {
            return $data;
        });
    }

}
