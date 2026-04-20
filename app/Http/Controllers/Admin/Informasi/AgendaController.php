<?php

namespace App\Http\Controllers\Admin\Informasi;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Http\Requests\AgendaRequest;
use App\Services\AgendaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function __construct(
        protected AgendaService $service
    ) {}

    public function index(): View
    {
        return view('informasi.agenda.index', [
            'agenda' => $this->service->getAllAgenda()
        ]);
    }

    public function create(): View
    {
        return view('informasi.agenda.create');
    }

    public function store(AgendaRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil disimpan');
    }

    public function edit(Agenda $agenda): View
    {
        return view('informasi.agenda.edit', compact('agenda'));
    }

    public function update(AgendaRequest $request, Agenda $agenda): RedirectResponse
    {
        $this->service->update($agenda, $request->validated());

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil diupdate');
    }

    public function destroy(Agenda $agenda): RedirectResponse
    {
        $this->service->delete($agenda);

        return redirect()->route('agenda.index')
            ->with('success', 'Agenda berhasil dihapus');
    }
}