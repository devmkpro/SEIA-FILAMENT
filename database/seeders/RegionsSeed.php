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
        $this->BASE_URL = 'https://raw.githubusercontent.com/devmkpro/localidades-ibge/main';
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
        $response = json_decode(file_get_contents($this->BASE_URL . '/estados.json'), true);
        return $limit ? array_slice($response, 0, $limit) : $response;
    }

    protected function getCities(string $uf): array
    {
        $response = json_decode(file_get_contents($this->BASE_URL . "/municipios.json"), true);
        $response = array_filter($response, function ($city) use ($uf) {
            return $city['microrregiao']['mesorregiao']['UF']['sigla'] === $uf;
        });
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
