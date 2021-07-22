<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\CommonJsonApiReturnsTrait;
use App\Models\Developer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DeveloperController extends Controller
{
    use CommonJsonApiReturnsTrait;

    private $orderableColumns = ['nome', 'sexo', 'idade', 'hobby', 'data_nascimento', 'created_at'];


    /**
     * Exibe uma listagem de Developers com opções de ordenação, busca e paginação.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $validatedQuery = $this->getValidatedIndexRequest($request);
            $developerBuilder = $this->getDeveloperQueryBuilder($validatedQuery);
            if (property_exists($validatedQuery, 'search_term')) {
                $developerBuilder = $this->getQueryBuilderWithSearchClauses($developerBuilder, $validatedQuery->search_term);
            }

            if ($developerBuilder->count() == 0) {
                return response()->json($this->getErrorContent('Nenhum Desenvolvedor encontrado'), 404);
            }

            $pageSize = $validatedQuery->page_size ?? 10;
            $developersPaginator = $developerBuilder->paginate($pageSize);
            $developers = $developersPaginator->getCollection();
            $paginationInfo = $developersPaginator->toArray();
            unset($paginationInfo['data'], $paginationInfo['links']);

            $metadata = (object) [
                'query' => $this->getIndexQueryMetadata(),
                'pagination' => (object) $paginationInfo,
            ];
            $responseContent = $this->getSuccessContent($developers, $metadata);
            return response()->json($responseContent);
        } catch (\Exception $exception) {
            report($exception);
            return $this->getJsonResponseFromException('Não foi possível listar os Desenvolvedores', $exception);
        }
    }

    /**
     * Registrar um novo Developer na base de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $data = $this->getValidatedStoreUpdateRequest($request);
            DB::beginTransaction();
            $developer = Developer::create($data);
            DB::commit();
            return response()->json($this->getSuccessContent($developer), 201);
        } catch (\Exception $exception) {
            report($exception);
            return $this->getJsonResponseFromException('Não foi possível adicionar um Desenvolvedor', $exception);
        }
    }

    /**
     * Obtém dados completos de um Developer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $developer = Developer::findOrFail($id);
            $responseContent = $this->getSuccessContent($developer);
            return response()->json($responseContent, 200);
        } catch (\Exception $exception) {
            report($exception);
            return $this->getJsonResponseFromException("Não foi possível obter dados do Desenvolvedor com ID {$id}", $exception);
        }
    }

    /**
     * Atualiza os dados de um Developer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $data = $this->getValidatedStoreUpdateRequest($request);
            DB::beginTransaction();
            $developer = Developer::findOrFail($id);
            $developer->fill($data);
            $developer->save();
            DB::commit();
            return response()->json($this->getSuccessContent($developer), 200);
        } catch (\Exception $exception) {
            report($exception);
            return $this->getJsonResponseFromException("Não foi possível atualizar dados do Desenvolvedor com ID {$id}", $exception);
        }
    }

    /**
     * Exclui um Developer da base de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $developer = Developer::findOrFail($id);
            $responseContent = $this->getSuccessContent((object) $developer->toArray());
            $developer->delete();
            DB::commit();
            return response()->json($responseContent, 204);
        } catch (\Exception $exception) {
            DB::rollBack();
            report($exception);
            return $this->getJsonResponseFromException("Não foi possível excluir o Desenvolvedor com ID {$id}", $exception);
        }
    }


    /**
     * Valida as opções passadas por query string na request de listagem.
     *
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    private function getValidatedIndexRequest(Request $request)
    {
        $query = $this->validate($request, [
            'page_size' => 'integer|min:1|max:50',
            'page' => 'integer|min:1',
            'order_by' => [Rule::in($this->orderableColumns)],
            'order_by_direction' => [Rule::in(['ASC', 'DESC'])],
            'search_term' => ['min:1', 'max:100', 'regex:/^[\w\d\s]+$/'],
        ]);

        if (array_key_exists('order_by_asc', $query) && array_key_exists('order_by_desc', $query)) {
            unset($query['order_by_desc']);
        }

        if (array_key_exists('search_term', $query)) {
            $query['search_term'] = trim($query['search_term']);
            if (empty($query['search_term'])) {
                unset($query['search_term']);
            }
        }

        return (object) $query;
    }

    /**
     * Obtém o query builder inicial com ordenação definida pela query ou padrão (created_at).
     *
     * @param $validatedQuery
     * @return Builder
     */
    private function getDeveloperQueryBuilder($validatedQuery)
    {
        $orderByColumn = 'created_at';
        if (property_exists($validatedQuery, 'order_by')) {
            $orderByColumn = $validatedQuery->order_by;
        }
        $orderByDirection = 'ASC';
        if (property_exists($validatedQuery, 'order_by_direction')) {
            $orderByDirection = $validatedQuery->order_by_direction;
        }
        return Developer::orderBy($orderByColumn, $orderByDirection);
    }

    /**
     * Tenta de várias formas adicionar cláusulas orWhere com base no conteúdo do termo de busca.
     *
     * @param Builder $queryBuilder
     * @param string $searchTerm
     * @return Builder
     */
    private function getQueryBuilderWithSearchClauses(Builder $queryBuilder, string $searchTerm)
    {
        $searchTerm = Str::upper($searchTerm);
        $searchTerm = preg_replace("/[^[:alnum:][:space:]]/u", ' ', $searchTerm);
        $searchTerm = preg_replace("/\s+/", ' ', $searchTerm);
        $words = Str::contains($searchTerm, ' ') ? explode(' ', $searchTerm) : [$searchTerm];

        foreach ($words as $word) {
            $queryBuilder->orWhereRaw('UPPER(nome) LIKE ?', ["%{$word}%"]);
            $queryBuilder->orWhereRaw('UPPER(hobby) LIKE ?', ["%{$word}%"]);
        }

        $number = intval($searchTerm);
        if ($number > 0) {
            $queryBuilder->orWhere('idade', $number);
            $queryBuilder->orWhereDay('data_nascimento', $number);
            $queryBuilder->orWhereMonth('data_nascimento', $number);
            $queryBuilder->orWhereYear('data_nascimento', $number);
        }

        if (in_array($searchTerm, ['M', 'MASC', 'MASCULINO'])) {
            $queryBuilder->orWhere('sexo', 'M');
        }

        if (in_array($searchTerm, ['F', 'FEM', 'FEMININO'])) {
            $queryBuilder->orWhere('sexo', 'F');
        }

        return $queryBuilder;
    }

    /**
     * Obtém um array de objetos com informações sobre como usar cada opção de query.
     *
     * @return object[]
     */
    private function getIndexQueryMetadata()
    {
        return [
            'page_size' => (object) [
                'type' => 'integer',
                'description' => 'Paginação. Quantidade máxima de itens em uma página.',
            ],
            'page' => (object) [
                'type' => 'integer',
                'description' => 'Paginação. Número da página a ser acessada.',
            ],
            'order_by' => (object) [
                'type' => 'string',
                'values' => $this->orderableColumns,
                'description' => 'Ordenação. Nome da coluna pela qual os itens devem ser ordenados.',
            ],
            'order_by_direction' => (object) [
                'type' => 'string',
                'values' => ['ASC', 'DESC'],
                'description' => 'Ordenação. Direção da ordenação dos itens, usado apenas quando há "order_by" na query.',
            ],
            'search_term' => (object) [
                'type' => 'string',
                'description' => 'Busca. Termo a ser procurado nos atributos da entidade.',
            ],
        ];
    }

    /**
     * Valida os dados passados para request de store ou update, com a diferenciação necessária.
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    private function getValidatedStoreUpdateRequest(Request $request)
    {
        $requiredPrefix = $request->method() === 'POST' ? 'required|' : '';
        $validatedData = $this->validate($request, [
            'nome' => $requiredPrefix . 'max:100',
            'data_nascimento' => $requiredPrefix . 'date_format:Y-m-d',
            'sexo' => 'nullable',
            'hobby' => 'nullable|max:500',
        ]);
        if (array_key_exists('data_nascimento', $validatedData)) {
            $dataNascimento = Date::createFromFormat('Y-m-d', $validatedData['data_nascimento']);
            $idadeHoje = $dataNascimento->diffInYears(Date::now());
            $validatedData = array_merge($validatedData, ['idade' => $idadeHoje]);
        }
        return $validatedData;
    }

}
