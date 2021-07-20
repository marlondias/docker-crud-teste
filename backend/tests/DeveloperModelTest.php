<?php

use Illuminate\Support\Facades\Schema;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use App\Models\Developer;

class DeveloperModelTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function database_is_set_up()
    {
        $tableName = 'developers';
        $columnNames = [
            'nome',
            'sexo',
            'idade',
            'hobby',
            'data_nascimento'
        ];
        $this->assertTrue(Schema::hasTable($tableName), "Tabela '{$tableName}' não foi encontrada no banco de dados.");
        $this->assertTrue(Schema::hasColumns($tableName, $columnNames), "Tabela '{$tableName}' não tem todas as colunas.");
    }

    /**
     * @test
     */
    public function accessor_sexo_extenso_is_always_string()
    {
        $sexos = ['M', 'F', null];
        foreach ($sexos as $sexo) {
            $developer = Developer::factory()->make(['sexo' => $sexo]);
            $this->assertIsString($developer->sexo_extenso, 'Atributo "sexo_extenso" deve retornar string.');
        }
    }

    /**
     * @test
     */
    public function accessor_data_nascimento_br_returns_brazilian_format()
    {
        $developers = Developer::factory()->count(3)->make();
        foreach ($developers as $developer) {
            $date = Date::createFromFormat('d/m/Y', $developer->data_nascimento_br);
            $this->assertEquals($developer->data_nascimento, $date->format('Y-m-d'),
                'Data obtida com "data_nascimento_br" é diferente da data de nascimento cadastrada.');
        }
    }

    /**
     * @test
     */
    public function nome_attribute_is_always_saved_in_title_case()
    {
        $names = collect(['joão', 'maria', 'íris', 'jojô', 'érica', 'conceição', 'müller']);
        for ($i = 0; $i < 3; $i++) {
            $fullname = $names->shuffle()->take(3)->implode(' ');
            $developer = Developer::factory()->make(['nome' => $fullname]);
            $this->assertEquals(Str::title($fullname), $developer->nome);
        }
    }

    /**
     * @test
     */
    public function sexo_attribute_is_saved_as_null_when_not_valid()
    {
        $sexosInvalidos = ['x', 'masc', 'fem', true, false, 0, 1];
        foreach ($sexosInvalidos as $sexo) {
            $developer = Developer::factory()->make(['sexo' => $sexo]);
            $this->assertNull($developer->sexo, 'Sexo inválido deve ser salvo como null');
        }
    }

    /**
     * @test
     */
    public function sexo_attribute_is_saved_as_capital_m_or_f_when_valid()
    {
        $sexosValidos = ['m', 'M', 'f', 'F'];
        foreach ($sexosValidos as $sexo) {
            $developer = Developer::factory()->make(['sexo' => $sexo]);
            $this->assertEquals(Str::upper($sexo), $developer->sexo, 'Sexo válido deve ser versão maiúscula da entrada');
            $this->assertTrue(in_array($developer->sexo, ['M', 'F']), 'Sexo válido deve ser M ou F');
        }
    }

}
