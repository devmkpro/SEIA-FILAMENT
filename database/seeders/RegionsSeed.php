<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionsSeed extends Seeder
{
    protected $BASE_URL;

    public function __construct()
    {
        $this->BASE_URL = 'https://servicodados.ibge.gov.br/api/v1/localidades';
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = $this->getStates(2);
        foreach ($states as $state) {
            $modelState = $this->createState($state);
            $cities = $this->getCities($state['sigla']);
            foreach ($cities as $city) {
                $this->createCity($city, $modelState->id);
            }
        }
    }

    protected function getStates(int $limit = null): array
    {
        $response = json_decode(file_get_contents($this->BASE_URL . '/estados'), true);
        return $limit ? array_slice($response, 0, $limit) : $response;
    }

    protected function getCities(string $uf): array
    {
        $response = json_decode(file_get_contents($this->BASE_URL . "/estados/{$uf}/municipios"), true);
        return $response;
    }

    protected function createState(array $state): State
    {
        $model = State::create([
            'name' => $state['nome'],
            'ibge_code' => $state['id']
        ]);

        return $model;
    }

    protected function createCity(array $city, string $uf): void
    {
        City::create([
            'name' => $city['nome'],
            'ibge_code' => $city['id'],
            'state_id' => $uf
        ]);
    }
}
