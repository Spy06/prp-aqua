<?php

namespace Database\Seeders;

use App\Models\BosqSubArea;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BosqSubAreaPicSeeder extends Seeder
{
    public function run(): void
    {
        $mapping = [
            'SBO Filler Line 1'       => ['18633', '20015', '20364'],
            'SBO Filler Line 2'       => ['21170', '21761', '19567'],
            'SBO Filler Line 3'       => ['18977', '19564', '20665'],
            'Ergo Line 5'             => ['19597', '18631', '23634'],
            'End Off Line 1'          => ['18633', '20015', '20364'],
            'End Off Line 2'          => ['21170', '21761', '19567'],
            'End Off Line 3'          => ['18977', '19564', '20665'],
            'End Off Line 5'          => ['19597', '18631', '23634'],
            'Storage Preform Existing'=> ['18977', '19564', '20665'],
            'Storage Preform Line 5'  => ['19597', '18631', '23634'],
            'WT Existing'             => ['21170', '21761', '19567'],
            'WT Line 5'               => ['19597', '18631', '23634'],
            'Husky'                   => ['18633', '20015', '20364'],
            'Sumber 1'                => ['21170', '21761', '19567'],
            'Sumber 3'                => ['21170', '21761', '19567'],
            'Sumber 4'                => ['21170', '21761', '19567'],

            'Gudang Material Existing'=> ['18986'],
            'Gudang Material Cimex'   => ['18986'],
            'Gudang Material Line 5'  => ['18986'],
            'Gudang Produk Existing'  => ['19973', '19562', '22222'],
            'Gudang Produk Cimex'     => ['19973', '19562', '22222'],
            'Loading Unloading Produk'=> ['19973', '19562', '22222'],
            'Loading Unloading Material'=> ['18986'],
            'Gudang Kimia'            => ['18986'],
            'Gudang Afval'            => ['18986'],
            'Gudang B3'               => ['18986'],
            'Tangki Solar'            => ['20169'],
            'Sparepart'               => ['20169'],

            'LAB Fiskim'              => ['18629'],
            'LAB Mikro'               => ['18629'],
            'Ruang IPC'               => ['19792'],
            'Ruang IMC'               => ['18816'],
            'Ruang Sample IMC'        => ['18816'],
            'Ruang HPU'               => ['18816'],
            'Office QA'               => ['20663'],

            'Toilet'                  => ['03758', '3758'],
            'Ruang Meeting'           => ['03758', '3758'],
            'Lobby'                   => ['03758', '3758'],

            'Workshop'                => ['18715'],
            'Soft Water'              => ['20427'],
            'Chiller'                 => ['20427'],
            'Kompresor'               => ['20427'],
            'AHU'                     => ['20427'],
            'Travo'                   => ['20427'],

            'Green House'             => ['21749'],
        ];

        foreach ($mapping as $subAreaName => $niks) {
            $subAreas = BosqSubArea::where('nama_sub_area', $subAreaName)->get();
            foreach ($subAreas as $sa) {
                foreach ($niks as $nik) {
                    $user = User::where('nik', $nik)->first();
                    if ($user) {
                        $sa->pics()->syncWithoutDetaching([$user->id]);
                    }
                }
            }
        }
    }
}
