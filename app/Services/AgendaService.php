<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Collection;

class AgendaService
{
    public function getAllAgenda(): Collection
    {
        return Agenda::latest()->get();
    }

    public function store(array $data): Agenda
    {
        $tahunAktif = TahunAjaran::latest()->first();
        $data['tahun_ajaran_id'] = $tahunAktif?->id;
        return Agenda::create($data);
    }

    public function update(Agenda $agenda, array $data): bool
    {
        return $agenda->update($data);
    }

    public function delete(Agenda $agenda): bool
    {
        return $agenda->delete();
    }
}