<?php

namespace App\Repositories;

use App\Exceptions\AppError;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseRepository
{
    protected $model;
    private $defaultPaginator = 100;
    private $limitPaginator = 500;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getModelClass()
    {
        return $this->model::class;
    }

    protected function defaultQuery(Model $model, array $select = [], array $relations = [], array $relationsCount = [], string $exercicio = null, bool $removeAudits = false, bool $removeUserLogs = false, array $relationsSum = [])
    {
        $currentModel = $model->query();

        if (count($select) > 0) {
            $currentModel->select($select);
        }

        if (count($relations) > 0) {
            $currentModel->with($relations);
        }

        if (count($relationsCount) > 0) {
            $currentModel->withCount($relationsCount);
        }

        if ($relationsSum) {
            foreach ($relationsSum as $relation) {
                $currentModel->withSum(...$relation);
            }
        }

        if (Schema::hasColumn($model->getTable(), 'exercicio')) {
            if (is_null($exercicio)) {
                $exercicio = session()->exists('exercicio') ? session('exercicio') : date('Y');
            }
            $currentModel->where('exercicio',  $exercicio);
        }

        if ($removeAudits) {
            $currentModel->without('audits');
        }

        if ($removeUserLogs) {
            $currentModel->without(['usuarioCadastro', 'usuarioAlteracao', 'usuarioExclusao']);
        }

        return $currentModel;
    }

    private function baseFind(Model $model, array $select = [], array $relations = [], array $relationsCount = [], string $exercicio = null,  bool $removeAudits = false, bool $removeUserLogs = false, array $relationsSum = [])
    {
        $currentModel = $this->defaultQuery($model, $select, $relations, $relationsCount, $exercicio, $removeAudits, $removeUserLogs, $relationsSum);
        return $currentModel;
    }

    public function baseFindByKey(Model $model, string|array $key, int|string|array $find, array $select = [], array $relations = [])
    {
        $currentModel = $this->defaultQuery($model, $select, $relations);

        if (is_array($key) && is_array($find)) {
            for ($i = 0; $i < count($key); $i++) {
                $currentModel->where($key[$i], $find[$i]);
            }
        } else {
            $currentModel->where($key, $find);
        }

        return $currentModel;
    }

    public function baseFindByCondition(Model $model, string|array $condition, array $select = [], array $relations = [], array $relationsSum = [], array $relationsCount = [])
    {
        $currentModel = $this->defaultQuery(model: $model, select: $select, relations: $relations, relationsSum: $relationsSum, relationsCount: $relationsCount);

        if (is_array($condition) && is_array($condition[0])) {
            for ($i = 0; $i < count($condition); $i++) {
                $currentModel->where(...$condition[$i]);
            }
        } else {
            $currentModel->where(...$condition);
        }
        return $currentModel;
    }

    public function find(int $id, array $select = [], array $relations = [], array $relationsCount = [], string $pluck = null, array $relationsSum = [])
    {
        $model = $this->baseFind(model: $this->model, select: $select, relations: $relations, relationsCount: $relationsCount, relationsSum: $relationsSum);
        $model = $model->findOrFail($id);

        if ($pluck) {
            return $model->$pluck;
        }

        $model['id_anterior'] = $this->model->where('id', '<', $model->id)->select('id')->orderby('id', 'desc')->pluck('id')->first();
        $model['id_proximo'] = $this->model->where('id', '>', $model->id)->select('id')->orderby('id', 'asc')->pluck('id')->first();
        return $model;
    }

    public function findOrCreate(string|array $key, int|string|array $find, array $dados)
    {
        $model = $this->baseFindByKey($this->model, $key, $find);

        $model = $model->first();

        if ($model) {
            return $model;
        }

        return $this->create($dados);
    }

    public function findAndUpdate(string|array $key, int|string|array $find, array $dados)
    {
        $model = $this->baseFindByKey($this->model, $key, $find);

        $model = $model->firstOrFail();

        $model->update($dados);

        return $model;
    }

    public function findByKey(string|array $key, int|string|array $find, array $select = [], array $relations = [], string $pluck = null, bool $exists = false)
    {
        $model = $this->baseFindByKey($this->model, $key, $find, $select, $relations);

        if ($pluck) {
            return $model->valueOrFail($pluck);
        }

        if ($exists) {
            return $model->first();
        }
        return $model->firstOrFail();
    }

    public function findByCondition(
        string|array $condition,
        array $select = [],
        array $relations = [],
        string $exercicio = '2023'
    ) {
        $model = $this->baseFindByCondition($this->model, $condition, $select, $relations);
        return $model->first();
    }

    public function findAll(
        array $select = [],
        array $relations = [],
        bool|int $paginator = false,
        string $search = null,
        string|array $sortField = null,
        string $sortOrder = 'desc',
        string $exercicio = null,
        array $searchFields = [],
        bool $removeAudits = false,
        bool $removeUserLogs = false,
        array $relationsSum = [],
        array $relationsCount = [],
        string $pluck = null
    ) {
        $model = $this->baseFind(model: $this->model, select: $select, relations: $relations, exercicio: $exercicio, removeAudits: $removeAudits, removeUserLogs: $removeUserLogs, relationsSum: $relationsSum, relationsCount: $relationsCount);

        if ($pluck) {
            return $model->get()->pluck($pluck);
        }

        if ($search && count($searchFields) > 0) {
            $model->where($searchFields[0], 'like', "$search%");
            for ($i = 1; $i < count($searchFields); $i++) {
                $model->orWhere($searchFields[$i], 'like', "$search%");
            }
        } else if ($search) {
            $model->where('nome', 'like', "$search%");
        }

        if ($sortField) {
            if (count($select) > 0 && !in_array($sortField, $select))
                throw new AppError('Argumento Inválido');

            if (is_array($sortField)) {
                foreach ($sortField as $sort) {
                    $model->orderBy($sort, $sortOrder);
                }
            } else {
                $model->orderBy($sortField, $sortOrder);
            }
        } else {
            $model->orderBy('id', $sortOrder);
        }

        if ($paginator) {

            if (is_numeric($paginator)) {
                if ($paginator < 500) {
                    $paginate = intval($paginator);
                } else {
                    $paginate = $this->limitPaginator;
                }
            } else {
                $paginate = $this->defaultPaginator;
            }

            return $model->paginate($paginate);
        }


        return $model->get();
    }

    //Refatorar
    public function findAllByKey(string|array $keys, int|string $find, array $select = [], array $relations = [], bool|int $paginator = false, string $sortField = null, string $sortOrder = null, string $exercicio = null, bool $removeAudits = false, bool $removeUserLogs = false)
    {
        $model = $this->baseFind(model: $this->model, select: $select, relations: $relations, exercicio: $exercicio, removeAudits: $removeAudits, removeUserLogs: $removeUserLogs);

        if (is_array($keys)) {
            foreach ($keys as $index => $key) {
                if ($index == 0) {
                    $model->where($key, 'LIKE', "$find%");
                } else {
                    $model->orWhere($key, 'LIKE', "$find%");
                }
            }
            return $model->get();
        }

        return $model->where($keys, 'LIKE', "$find%")->get();
    }

    public function findAllByCondition(
        string|array $condition,
        array $select = [],
        array $relations = [],
        bool|int $paginator = false,
        string $sortField = null,
        string $sortOrder = null,
        string $exercicio = '2023',
        array $relationsSum = [],
        array $relationsCount = []
    ) {
        $model = $this->baseFindByCondition($this->model, $condition, $select, $relations, $relationsSum, $relationsCount);

        if ($sortField && $sortOrder) {
            $model->orderBy($sortField, $sortOrder);
        }

        if ($paginator) {

            if (is_numeric($paginator)) {
                if ($paginator < 500) {
                    $paginate = intval($paginator);
                } else {
                    $paginate = $this->limitPaginator;
                }
            } else {
                $paginate = $this->defaultPaginator;
            }

            return $model->paginate($paginate);
        }


        return $model->get();
    }

    public function search(string|array $search, array $searchFields = [], array $select = [], array $relations = [], string $sortField = null, string $sortOrder = null, bool|int $paginator = false, bool $useAndOperador = false, bool $exact = false)
    {
        $model = $this->defaultQuery($this->model, $select, $relations);
        if ($search && count($searchFields) > 0) {

            $multipleSearchs = is_array($search) && count($search) > 0;
            $searchValue = $multipleSearchs ? $search[0] : $search;
            $condition = 'like';
            if ($exact) {
                $condition = '=';
            } else {
                $searchValue = "%$searchValue%";
            }
            $model->where($searchFields[0], $condition, $searchValue);

            for ($i = 1; $i < count($searchFields); $i++) {
                $searchValue = $multipleSearchs ? $search[$i] : $search;
                if (!$exact) $searchValue = "%$searchValue%";
                if (is_null($searchValue)) continue;

                if ($useAndOperador) {
                    $model->where($searchFields[$i], $condition, $searchValue);
                } else {
                    $model->orWhere($searchFields[$i], $condition, $searchValue);
                }
            }
        } else if ($search) {
            $model->where('nome', 'like', "$search%");
        }

        if ($sortField && $sortOrder) {
            $model->orderBy($sortField, $sortOrder);
        }

        if ($exact) {
            return $model->first();
        }

        if ($paginator) {

            if (is_numeric($paginator)) {
                if ($paginator < 500) {
                    $paginate = intval($paginator);
                } else {
                    $paginate = $this->limitPaginator;
                }
            } else {
                $paginate = $this->defaultPaginator;
            }

            return $model->paginate($paginate);
        }

        return $model->limit(15)->get();
    }

    public function create($dados)
    {
        return $this->model->create($dados);
    }

    public function createMany(array $dados)
    {
        $results = [];

        foreach ($dados as $dado) {
            $result = $this->model->create($dado);
            array_push($results, $result);
        }

        return $results;
    }

    public function createOrUpdate(string|array $key, int|string|array $find, array $dados)
    {
        $model = $this->baseFindByKey($this->model, $key, $find);

        $model = $model->first();

        //Se existir atualiza e retorna a model
        if ($model) {
            $model->update($dados);
            return $model;
        }

        //Se não existir cria e retorna a model
        return $this->model->create($dados);
    }

    public function update(int $id, array $dados)
    {
        $model = $this->model->query();
        $model = $model->findOrFail($id);
        $model->update($dados);
        return $model;
    }

    public function delete(int $id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function deleteByWhere(string|array $key, int|string|array $condition)
    {
        $model = $this->model->query();

        if (is_array($key) && is_array($condition)) {
            for ($i = 0; $i < count($key); $i++) {
                $model->where($key[$i], $condition[$i]);
            }
        } else {
            $model->where($key, $condition);
        }

        $model = $model->first();

        if (!$model) {
            throw new AppError('Erro ao excluir registro');
        }

        return $model->delete();
    }

    public function restore(int $id)
    {
        return $this->model->withTrashed()->find($id)->restore();
    }

    protected function getSelect(Model $model, array $select = ['id', 'nome'])
    {
        return $this->defaultQuery(model: $model, select: $select, removeAudits: true, removeUserLogs: true)->get();
    }

    public function getSelects(): array
    {
        return [];
    }
}
