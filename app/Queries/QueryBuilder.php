<?php

namespace App\Queries;

use App\Filters\QueryFilter;
use http\Exception\BadMethodCallException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class QueryBuilder extends Builder
{
    private $filters;
    public function whereQuery($subquery, $operator,  $value= null)
    {
        $this->addBinding($subquery->getBindings());

        //añade a la consulta de forma de una cadena que lo genera tosql()
        $this->where(DB::raw("({$subquery -> toSql()})"),$operator, $value); 	//hemos extraido la consulta principal la consulta qu esta aparte(primer parametro s espera una cadena de texto)

        return $this;
    }

    public function onlyTrashedIf($value)
    {
        if($value){
            $this -> onlyTrashed();
        }
        return $this;

    }
    //Solo pasamos los filtros ya no la consulta
    public function filterBy(QueryFilter $filters, array $data)
    {
        $this->filters = $filters;

        return $filters->applyTo($this, $data);
    }

    //Recibiremos un array con los filtros de manera opcional
    public function applyFilters(array $data = null)
    {
        return $this->filterBy($this->newQueryFilter(), $data ?: request()->all());
    }

    //Comprobara si existe el metodo newQueryFilter ,es un metodo general
    public function newQueryFilter()
    {
        if (method_exists($this->model, 'newQueryFilter')) {
            return $this->model->newQueryFilter();
        }

        //Pregunta si la clase existe , el nombre del modelo
        if (class_exists($filterClass = '\App\Filters\\' . get_class($this->model). 'Filter')) {
            return new $filterClass;
        }

        throw new BadMethodCallException(
            sprintf('No query filter was found for the model [%s]', get_class($this->model))
        );
    }

    //Viene del core de laravel y se redefine, podremos eliminar del usercontroller porque lo heredaremos
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        $paginator = parent::paginate($perPage, $columns, $pageName, $page);

        if ($this->filters) {
            $paginator->appends($this->filters->valid());
        }

        return $paginator;
    }
}
