<?php
namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use App\Models\ContenusFormation;
use App\Models\Formations;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContenusFormationExport;

class ContenusFormationController extends Component
{
    public function liste_contenue()
    {
        $contenues = ContenusFormation::with('formation')
            ->orderBy('id')
            ->paginate(4);

        $formations = Formations::all();
        return view('livewire.example-laravel.contenusformation-management', compact('contenues', 'formations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomchap' => 'required|string',
            'nomunite' => 'required|string',
            'description' => 'nullable|string',
            'nombreheures' => 'required|integer',
            'formation_id' => 'required|exists:formations,id',
        ]);
    
        try {
            $contenu = ContenusFormation::create($request->all());
            return response()->json(['success' => 'Contenu créé avec succès', 'contenu' => $contenu]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomchap' => 'required|string',
            'nomunite' => 'required|string',
            'description' => 'nullable|string',
            'nombreheures' => 'required|integer',
            'formation_id' => 'required|exists:formations,id',
        ]);

        try {
            $contenue = ContenusFormation::findOrFail($id);
            $contenue->update($request->all());
            return response()->json(['success' => 'Contenu modifié avec succès', 'contenue' => $contenue]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function delete_contenue($id)
    {
        try {
            $contenue = ContenusFormation::findOrFail($id);
            $contenue->delete();
            return response()->json(['success' => 'Contenu supprimé avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $contenu = ContenusFormation::findOrFail($id);
            return response()->json(['contenu' => $contenu]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function render()
    {
        return $this->liste_contenue();
    }
}