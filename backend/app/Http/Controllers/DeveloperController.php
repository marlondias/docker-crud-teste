<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\CommonJsonApiReturnsTrait;
use App\Models\Developer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DeveloperController extends Controller
{
    use CommonJsonApiReturnsTrait;

    private $orderableColumns = ['nome', 'sexo', 'idade', 'hobby', 'data_nascimento', 'created_at'];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $validatedQuery = $this->validateIndexRequest($request);
            $developerBuilder = $this->getDeveloperQueryBuilder($validatedQuery);
            if (property_exists($validatedQuery, 'search_term')) {
                $developerBuilder = $this->getQueryBuilderWithSearchClauses($developerBuilder, $validatedQuery->search_term);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Developer $request)
    {
        try {

            //TBD
            $x = Developer::findOrFail(999);
        } catch (\Exception $exception) {
            report($exception);
            return $this->getJsonResponseFromException('Não foi possível adicionar um Desenvolvedor', $exception);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            //TBD
        } catch (\Exception $exception) {
            report($exception);
            return $this->getJsonResponseFromException("Não foi possível obter dados do Desenvolvedor com ID {$id}", $exception);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Developer $request, $id)
    {
        try {
            DB::beginTransaction();
            $developer = Developer::findOrFail($id);

            //TBD

            DB::commit();
            $responseContent = $this->getSuccessContent([(object) $developer->toArray()]);
            return response()->json($responseContent, 200);
        } catch (\Exception $exception) {
            report($exception);
            return $this->getJsonResponseFromException("Não foi possível atualizar dados do Desenvolvedor com ID {$id}", $exception);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $developer = Developer::findOrFail($id);
            $responseContent = $this->getSuccessContent([(object) $developer->toArray()]);
            $developer->delete();
            DB::commit();
            return response()->json($responseContent, 200);
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
    private function validateIndexRequest(Request $request)
    {
        $query = $this->validate($request, [
            'page_size' => 'integer|min:1|max:50',
            'page' => 'integer|min:1',
            'order_by_asc' => [Rule::in($this->orderableColumns)],
            'order_by_desc' => [Rule::in($this->orderableColumns)],
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
        $orderByDirection = 'ASC';
        if (property_exists($validatedQuery, 'order_by_desc')) {
            $orderByColumn = $validatedQuery->order_by_desc;
            $orderByDirection = 'DESC';
        } else if (property_exists($validatedQuery, 'order_by_asc')) {
            $orderByColumn = $validatedQuery->order_by_asc;
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
        $words = explode(' ', Str::upper($searchTerm));
        foreach ($words as $word) {
            $queryBuilder->orWhereRaw('UPPER("nome") LIKE ?', ["%{$word}%"]);
            $queryBuilder->orWhereRaw('UPPER("hobby") LIKE ?', ["%{$word}%"]);
        }

        $number = intval($searchTerm);
        if ($number > 0) {
            $queryBuilder->orWhere('idade', $number);
            $queryBuilder->orWhereDay('data_nascimento', $number);
            $queryBuilder->orWhereMonth('data_nascimento', $number);
            $queryBuilder->orWhereYear('data_nascimento', $number);
        }

        if (in_array(Str::upper($searchTerm), ['M', 'MASC', 'MASCULINO'])) {
            $queryBuilder->orWhere('sexo', 'M');
        }

        if (in_array(Str::upper($searchTerm), ['F', 'FEM', 'FEMININO'])) {
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
            'order_by_asc' => (object) [
                'type' => 'string',
                'values' => $this->orderableColumns,
                'description' => 'Ordenação. Nome da coluna pela qual os itens devem ser ordenados, em ordem crescente.',
            ],
            'order_by_desc' => (object) [
                'type' => 'string',
                'values' => $this->orderableColumns,
                'description' => 'Ordenação. Nome da coluna pela qual os itens devem ser ordenados, em ordem decrescente.',
            ],
            'search_term' => (object) [
                'type' => 'string',
                'description' => 'Busca. Termo a ser procurado nos atributos da entidade.',
            ],
        ];
    }

}
