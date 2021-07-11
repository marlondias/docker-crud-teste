<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\CommonJsonApiReturnsTrait;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    use CommonJsonApiReturnsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            //TBD
        } catch (\Exception $exception) {
            report($exception);
            $response = $this->getErrorContent("Não foi possível listar os Desenvolvedores.", $exception);
            return response()->json($response, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            //TBD
        } catch (\Exception $exception) {
            report($exception);
            $response = $this->getErrorContent("Não foi possível adicionar um Desenvolvedor.", $exception);
            return response()->json($response, 500);
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
            $response = $this->getErrorContent("Não foi possível obter dados do Desenvolvedor com ID {$id}.", $exception);
            return response()->json($response, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            //TBD
        } catch (\Exception $exception) {
            report($exception);
            $response = $this->getErrorContent("Não foi possível atualizar dados do Desenvolvedor com ID {$id}.", $exception);
            return response()->json($response, 500);
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
            //TBD
        } catch (\Exception $exception) {
            report($exception);
            $response = $this->getErrorContent("Não foi possível excluir o Desenvolvedor com ID {$id}.", $exception);
            return response()->json($response, 500);
        }
    }
}
