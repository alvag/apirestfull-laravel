<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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
     */
    protected function showAll(Collection $collection, $code = 200)
    {
        if (!$collection->isEmpty()) {
            $transformer = $collection->first()->transformer;

            $collection = $this->filterData($collection, $transformer);
            $collection = $this->sortData($collection, $transformer);
            $collection = $this->transformData($collection, $transformer);
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
}
