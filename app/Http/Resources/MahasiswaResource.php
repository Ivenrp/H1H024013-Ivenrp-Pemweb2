<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id'       => $this->id,
            'nim'      => $this->nim,
            'nama'     => $this->nama,
            'email'    => $this->email,
            'angkatan' => $this->angkatan,
            'ipk'      => (float) $this->ipk,
            'aktif'    => $this->aktif,
            'program_studi' => $this->whenLoaded('programStudi', function () {
                return [
                    'id'      => $this->programStudi->id,
                    'kode'    => $this->programStudi->kode,
                    'nama'    => $this->programStudi->nama,
                    'jenjang' => $this->programStudi->jenjang,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];

        // TUGAS 3: filter kolom via ?fields=
        if ($request->filled('fields')) {
            $fields = explode(',', $request->query('fields'));
            $fields = array_intersect($fields, array_keys($data));

            return array_intersect_key($data, array_flip($fields));
        }

        return $data;
    }
}
