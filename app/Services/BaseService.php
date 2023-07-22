<?php

namespace App\Services;

use App\Exceptions\AppError;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BaseService
{
    protected $repository;

    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function isDebug()
    {
        return config('app.debug');
    }

    public function find(int $id, array $select = [], array $relations = [], array $relationsCount = [], string $pluck = null, array $relationsSum = [])
    {
        try {
            return $this->repository->find(id: $id, select: $select, relations: $relations, relationsCount: $relationsCount, pluck: $pluck, relationsSum: $relationsSum);
        } catch (\Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Registro não encontrado.');
        }
    }

    public function findByKey(string|array $key, int|string|array $find, array $select = [], array $relations = [], string $pluck = null, bool $exists = false)
    {
        try {
            return $this->repository->findByKey($key, $find, $select, $relations, $pluck, $exists);
        } catch (\Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Registro não encontrado.');
        }
    }

    public function findAll(array $select = [], array $relations = [], bool|int $paginator = false, string $search = null, string|array $sortField = null, string $sortOrder = 'desc', string $exercicio = null, array $searchFields = [], bool $removeAudits = false, bool $removeUserLogs = false, array $relationsSum = [], array $relationsCount = [], string $pluck = null)
    {
        try {
            return $this->repository->findAll($select, $relations, $paginator, $search, $sortField, $sortOrder, $exercicio, $searchFields, $removeAudits, $removeUserLogs, $relationsSum, $relationsCount, $pluck);
        } catch (Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao listar registros');
        }
    }

    public function findAllByKey(string|array $key, int|string $find, array $select = [], array $relations = [], bool|int $paginator = false, string $sortField = null, string $sortOrder = null, string $exercicio = null, bool $removeAudits = false, bool $removeUserLogs = false)
    {
        try {
            return $this->repository->findAllByKey($key, $find, $select, $relations, $paginator, $sortField, $sortOrder, $exercicio, $removeAudits, $removeUserLogs);
        } catch (Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao listar registros');
        }
    }

    public function findByCondition(string|array $condition, array $select = [], array $relations = [])
    {
        try {
            return $this->repository->findByCondition($condition, $select, $relations);
        } catch (Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao listar registros');
        }
    }

    public function search(string|array $search, array $searchFields = [], array $select = [], array $relations = [], string $sortField = null, string $sortOrder = null, bool|int $paginator = false, bool $useAndOperador = false, bool $exact = false)
    {
        try {
            return $this->repository->search($search, $searchFields, $select, $relations, $sortField, $sortOrder, $paginator, $useAndOperador, $exact);
        } catch (Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao buscar registros');
        }
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
        array $relationsCount = [],
    ) {
        try {
            return $this->repository->findAllByCondition($condition, $select, $relations, $paginator, $sortField, $sortOrder, $exercicio, $relationsSum, $relationsCount);
        } catch (Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao listar registros');
        }
    }

    public function create(array $dados, array $relations = [])
    {
        if (isset($dados['cnpj']) && !$this->validarCpfCnpj($dados['cnpj'])) {
            throw new AppError('CNPJ invalido');
        }

        try {
            return DB::transaction(function () use ($dados, $relations) {
                $result = $this->repository->create($dados);

                if (count($relations) > 0) {
                    foreach ($relations as $relation) {
                        if (isset($dados[$relation]) && count($dados[$relation]) > 0) {
                            if (isset($dados[$relation][0]) && is_array($dados[$relation][0])) {
                                $result->$relation()->createMany($dados[$relation]);
                            } else {
                                $result->$relation()->create($dados[$relation]);
                            }
                        }
                    }
                }
                return $result;
            });
        } catch (\Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao criar registro.');
        }
    }

    protected function beforeCreate(array $dados): array
    {
        // usar para validações e formatações

        return $dados;
    }

    public function advancedCreate(array $dados, array $relations = [])
    {
        try {
            return DB::transaction(function () use ($dados, $relations) {
                $dados = $this->beforeCreate($dados);
                $result = $this->create($dados, $relations);
                $this->afterCreate($dados, $result);

                return $result;
            });
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                throw ValidationException::withMessages($e->errors());
            } else {
                throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao criar registro.');
            }
        }
    }

    protected function afterCreate(array $originalData, mixed $createdData)
    {
        // usar para ações após criar o registro
        // Ex: upload de arquivos onde a pasta é organizado pelo id
    }

    public function update(int $id, array $dados, array $relations = [])
    {
        try {
            return DB::transaction(function () use ($id, $dados, $relations) {
                $result = $this->repository->update($id, $dados);
                $this->updateRelations($dados, $relations, $result);
                return $result;
            });
        } catch (\Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao atualizar registro.');
        }
    }

    private function convertToSnakeCase(string $value): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $value, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    public function updateRelations(array $dados, array $relations, mixed $result)
    {
        try {
            if (count($relations) > 0) {
                foreach ($relations as $relation) {
                    $relation_snake_case = $this->convertToSnakeCase($relation);
                    if (isset($dados[$relation_snake_case]) && count($dados[$relation_snake_case]) > 0) {
                        if (isset($dados[$relation_snake_case][0]) && is_array($dados[$relation_snake_case][0])) {
                            $current_relations = $result->$relation;
                            $new_relations = collect($dados[$relation_snake_case])->filter((function ($item) {
                                return !isset($item['id']);
                            }))->all();

                            $updated_relations = collect($dados[$relation_snake_case])->filter((function ($item) {
                                return isset($item['id']);
                            }))->all();
                            $updated_relations_id = collect($updated_relations)->pluck('id')->all();

                            $deleted_relations = $current_relations->whereNotIn('id', $updated_relations_id)->all();
                            if (count($deleted_relations) > 0) {
                                foreach ($deleted_relations as $deleted) {
                                    $relation_result = $result->$relation()->where('id', $deleted['id'])->first();
                                    $relation_result->delete();
                                }
                            }

                            if (count($new_relations) > 0) {
                                $result->$relation()->createMany($new_relations);
                            }

                            if (count($updated_relations) > 0) {
                                foreach ($updated_relations as $updated) {
                                    $model = $result->$relation()->findOrFail($updated['id']);
                                    $model->update(collect($updated)->except(['id'])->all());
                                }
                            }
                        } else {
                            $current = $result->$relation()->first();
                            if (is_null($current)) {
                                $result->$relation()->create($dados[$relation_snake_case]);
                            } else {
                                $current->update($dados[$relation_snake_case]);
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao atualizar relações do registro.');
        }
    }

    protected function beforeUpdate(array $dados, int $id): array
    {
        // usar para validações e formatações

        return $dados;
    }

    public function advancedUpdate(int $id, array $dados, array $relations = [])
    {
        try {
            return DB::transaction(function () use ($id, $dados, $relations) {
                $dados = $this->beforeUpdate($dados, $id);
                $result = $this->update($id, $dados, $relations);
                $this->afterUpdate($dados, $result);

                return $result;
            });
        } catch (\Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao atualizar registro.');
        }
    }

    protected function afterUpdate(array $originalData, mixed $createdData)
    {
        // usar para ações após criar o registro
        // Ex: upload de arquivos onde a pasta é organizado pelo id
    }

    public function createOrUpdate(string|array $key, int|string|array $find, mixed $dados)
    {
        try {
            if (isset($dados['cnpj']) && !$this->validarCpfCnpj($dados['cnpj'])) {
                throw new AppError('CNPJ invalido');
            }

            return $this->repository->createOrUpdate($key, $find, $dados);
        } catch (Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao criar|atualizar registro.');
        }
    }

    public function delete(int $id, array $relationsCount = [])
    {
        try {
            if (count($relationsCount) > 0) {
                $model = $this->repository->find(id: $id, relationsCount: $relationsCount);
                foreach ($relationsCount as $relation) {
                    if ($model[$relation . '_count'] > 0) {
                        $qtd = $model[$relation . '_count'];
                        throw new AppError(
                            "Não é possível excluir este item porque existem outros registros associados a ele!"
                        );
                    }
                }
            }

            return $this->repository->delete($id);
        } catch (\Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao excluir registo.');
        }
    }

    public function deleteByWhere(string|array $key, int|string|array $condition)
    {
        try {
            return $this->repository->deleteByWhere($key, $condition);
        } catch (\Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao excluir registo(s).');
        }
    }

    public function restore(int $id)
    {
        return $this->repository->restore($id);
    }


    public function getSelects()
    {
        try {
            return $this->repository->getSelects();
        } catch (\Exception $e) {
            throw new AppError($this->isDebug() ? $e->getMessage() : 'Erro ao listar opções.');
        }
    }

    public function validarCpfCnpj($valor)
    {
        // Deixa apenas números no valor
        $valor = regexNumeros($valor);

        // Garante que o valor é uma string
        $valor = (string)$valor;

        // Valida CPF
        if ($this->verifica_cpf_cnpj($valor) === 'CPF') {
            // Retorna true para cpf válido
            return $this->valida_cpf($valor) && $this->verifica_sequencia(11, $valor);
        }
        // Valida CNPJ
        elseif ($this->verifica_cpf_cnpj($valor) === 'CNPJ') {
            // Retorna true para CNPJ válido
            return $this->valida_cnpj($valor) && $this->verifica_sequencia(14, $valor);
        }
        // Não retorna nada
        else {
            return false;
        }
    }

    /**
     * Verifica se é CPF ou CNPJ
     * Se for CPF tem 11 caracteres, CNPJ tem 14
     */

    private function verifica_cpf_cnpj($valor)
    {
        // Verifica CPF
        if (strlen($valor) === 11) {
            return 'CPF';
        }
        // Verifica CNPJ
        elseif (strlen($valor) === 14) {
            return 'CNPJ';
        }
        // Não retorna nada
        else {
            return false;
        }
    }

    /**
     * Multiplica dígitos vezes posições
     */

    private function calc_digitos_posicoes($digitos, $posicoes = 10, $soma_digitos = 0)
    {
        // Faz a soma dos dígitos com a posição
        // Ex. para 10 posições:
        //   0    2    5    4    6    2    8    8   4
        // x10   x9   x8   x7   x6   x5   x4   x3  x2
        //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
        for ($i = 0; $i < strlen($digitos); $i++) {
            // Preenche a soma com o dígito vezes a posição
            $soma_digitos = $soma_digitos + ($digitos[$i] * $posicoes);

            // Subtrai 1 da posição
            $posicoes--;

            // Parte específica para CNPJ
            // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
            if ($posicoes < 2) {
                // Retorno a posição para 9
                $posicoes = 9;
            }
        }

        // Captura o resto da divisão entre $soma_digitos dividido por 11
        // Ex.: 196 % 11 = 9
        $soma_digitos = $soma_digitos % 11;

        // Verifica se $soma_digitos é menor que 2
        if ($soma_digitos < 2) {
            // $soma_digitos agora será zero
            $soma_digitos = 0;
        } else {
            // Se for maior que 2, o resultado é 11 menos $soma_digitos
            // Ex.: 11 - 9 = 2
            // Nosso dígito procurado é 2
            $soma_digitos = 11 - $soma_digitos;
        }

        // Concatena mais um dígito aos primeiro nove dígitos
        // Ex.: 025462884 + 2 = 0254628842
        $cpf = $digitos . $soma_digitos;

        // Retorna
        return $cpf;
    }

    private function valida_cpf($valor)
    {
        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        $digitos = substr($valor, 0, 9);

        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        $novo_cpf = $this->calc_digitos_posicoes($digitos);

        // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
        $novo_cpf = $this->calc_digitos_posicoes($novo_cpf, 11);

        // Verifica se o novo CPF gerado é idêntico ao CPF enviado
        if ($novo_cpf === $valor) {
            // CPF válido
            return true;
        } else {
            // CPF inválido
            return false;
        }
    }

    private function valida_cnpj($valor)
    {
        // O valor original
        $cnpj_original = $valor;

        // Captura os primeiros 12 números do CNPJ
        $primeiros_numeros_cnpj = substr($valor, 0, 12);

        // Faz o primeiro cálculo
        $primeiro_calculo = $this->calc_digitos_posicoes($primeiros_numeros_cnpj, 5);

        // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
        $segundo_calculo = $this->calc_digitos_posicoes($primeiro_calculo, 6);

        // Concatena o segundo dígito ao CNPJ
        $cnpj = $segundo_calculo;

        // Verifica se o CNPJ gerado é idêntico ao enviado
        if ($cnpj === $cnpj_original) {
            return true;
        }
    }

    /**
     * Método para verifica sequencia de números
     */
    private function verifica_sequencia($multiplos, $valor)
    {
        // cpf
        for ($i = 0; $i < 10; $i++) {
            if (str_repeat($i, $multiplos) == $valor) {
                return false;
            }
        }

        return true;
    }
}
