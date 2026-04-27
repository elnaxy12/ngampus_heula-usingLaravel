<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    private function apiKey(): string
    {
        return env('RAJAONGKIR_API_KEY');
    }

    private function baseUrl(): string
    {
        return env('RAJAONGKIR_BASE_URL');
    }

    public function getProvinces()
    {
        $response = Http::withHeaders(['key' => $this->apiKey()])
            ->get($this->baseUrl() . 'destination/province');

        return response()->json($response->json());
    }

    public function getCities(Request $request)
    {
        $response = Http::withHeaders(['key' => $this->apiKey()])
            ->get($this->baseUrl() . 'destination/city/' . $request->input('province_id'));

        return response()->json($response->json());
    }

    public function getCost(Request $request)
    {
        $response = Http::withHeaders(['key' => $this->apiKey()])
            ->post($this->baseUrl() . 'calculate/domestic-cost', [
                'origin'      => $request->input('origin'),
                'destination' => $request->input('destination'),
                'weight'      => $request->input('weight'),
                'courier'     => $request->input('courier'),
            ]);

        return response()->json($response->json());
    }
}
