<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Http\Controllers\DeveloperController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Developer;

class DeveloperControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Developer::truncate();
    }

    /**
     * @test
     */
    public function index_returns_json_response_and_status_200_when_table_full()
    {
        Developer::factory()->count(3)->create();
        $response = (new DeveloperController())->index(new Request());
        $this->assertInstanceOf(JsonResponse::class, $response, 'Resposta deve ser JSON');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function index_returns_json_response_and_status_404_when_table_empty()
    {
        $response = (new DeveloperController())->index(new Request());
        $this->assertInstanceOf(JsonResponse::class, $response, 'Resposta deve ser JSON');
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @test
     * @depends index_returns_json_response_and_status_200_when_table_full
     */
    public function index_returns_data_as_array()
    {
        Developer::factory()->count(3)->create();
        $json = (new DeveloperController())->index(new Request())->getData();
        $this->assertIsArray($json->data);
    }

    /**
     * @test
     * @depends index_returns_json_response_and_status_200_when_table_full
     */
    public function index_returns_query_and_pagination_metadata()
    {
        Developer::factory()->count(3)->create();
        $json = (new DeveloperController())->index(new Request())->getData();
        $this->assertIsObject($json->metadata);
        $this->assertObjectHasAttribute('pagination', $json->metadata);
        $this->assertObjectHasAttribute('query', $json->metadata);
    }

    /**
     * @test
     * @depends index_returns_data_as_array
     */
    public function index_pagination_size_can_be_changed_by_query()
    {
        Developer::factory()->count(50)->create();
        $customPageSize = rand(2, 10);
        $request = new Request(['page_size' => $customPageSize]);
        $json = (new DeveloperController())->index($request)->getData();
        $this->assertCount($customPageSize, $json->data);
        $this->assertEquals($customPageSize, $json->metadata->pagination->per_page);
    }

    /**
     * @test
     */
    public function index_with_search_term_finds_relevant_items()
    {
        $searchTerm = collect(['asd', 'fgh', 'jkl', 'zxc'])->shuffle()->first();

        $developersNome = Developer::factory()->count(3)->create(['nome' => "{$searchTerm} da silva"]);
        $developersHobby = Developer::factory()->count(3)->create(['hobby' => "Brincar com {$searchTerm}"]);
        $relevantDevelopersIds = $developersNome->pluck('id')->merge($developersHobby->pluck('id'))->toArray();
        Developer::factory()->count(20)->create();

        $request = new Request(['search_term' => $searchTerm]);
        $json = (new DeveloperController())->index($request)->getData();
        $foundDevelopersIds = array_map(function($item) {
            return $item->id;
        }, $json->data);
        $difference = array_diff($foundDevelopersIds, $relevantDevelopersIds);
        $this->assertEmpty($difference);
    }

    /**
     * @test
     */
    public function store_adds_developer_and_returns_status_200_when_data_is_valid()
    {
        //
    }

    /**
     * @test
     */
    public function store_does_not_add_developer_and_returns_400_when_data_is_invalid()
    {
        //
    }

    /**
     * @test
     */
    public function update_changes_developer_and_returns_status_200_when_data_is_valid()
    {
        //
    }

    /**
     * @test
     */
    public function update_does_not_change_developer_and_returns_400_when_data_is_invalid()
    {
        //
    }

    /**
     * @test
     */
    public function delete_returns_developer_data_and_status_204_when_id_found()
    {
        $developer = Developer::factory()->create();
        $developerData = (object) $developer->toArray();
        $response = (new DeveloperController())->destroy($developer->id);
        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals($developerData, $response->getData()->data);
    }

    /**
     * @test
     */
    public function delete_returns_status_400_when_id_not_found()
    {
        $this->markTestSkipped(); // Pedido diz que o código é 400;
        $anyId = rand(1, 100);
        $response = (new DeveloperController())->destroy($anyId);
        $this->assertEquals(404, $response->getStatusCode());
    }

}
