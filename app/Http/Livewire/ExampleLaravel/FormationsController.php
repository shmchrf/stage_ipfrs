<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Formations;
use App\Models\ContenusFormation;
use App\Exports\FormationsExport;
use Maatwebsite\Excel\Facades\Excel;

class FormationsController extends Component
{
    public function liste_formation()
    {
        $formations = Formations::orderBy('nom')->paginate(4);
        return view('livewire.example-laravel.formations-management', compact('formations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'duree' => 'required|integer',
            'prix' => 'required|integer',
        ]);

        $formation = new Formations([
            'code' => $request->code,
            'nom' => $request->nom,
            'duree' => $request->duree,
            'prix' => $request->prix,
        ]);

        if ($formation->save()) {
            return response()->json(['status' => 200, 'message' => 'Formation ajoutée avec succès.']);
        } else {
            return response()->json(['status' => 400, 'message' => 'Erreur lors de l\'ajout de la formation.']);
        }
    }

    public function update(Request $request, $id)
    {
        $formation = Formations::find($id);

        if ($formation) {
            $request->validate([
                'code' => 'required|string|max:255',
                'nom' => 'required|string|max:255',
                'duree' => 'required|integer',
                'prix' => 'required|integer',
            ]);

            $formation->update($request->all());

            return response()->json(['status' => 200, 'message' => 'Formation modifiée avec succès!']);
        } else {
            return response()->json(['status' => 404, 'message' => 'Formation non trouvée.']);
        }
    }

    public function delete_formation($id)
    {
        $formation = Formations::find($id);

        if ($formation) {
            $contenus = ContenusFormation::where('formation_id', $id)->get();

            if ($contenus->isNotEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Cette formation a des contenus associés. Voulez-vous vraiment la supprimer ainsi que tous ses contenus?',
                    'has_contents' => true
                ]);
            } else {
                $formation->delete();
                return response()->json(['status' => 200, 'message' => 'Formation supprimée avec succès.']);
            }
        } else {
            return response()->json(['status' => 404, 'message' => 'Formation non trouvée.']);
        }
    }

    public function confirm_delete_formation(Request $request, $id)
    {
        $formation = Formations::find($id);

        if ($formation) {
            ContenusFormation::where('formation_id', $id)->delete();
            $formation->delete();

            return response()->json(['status' => 200, 'message' => 'Formation et ses contenus supprimés avec succès.']);
        } else {
            return response()->json(['status' => 404, 'message' => 'Formation non trouvée.']);
        }
    }

    public function export()
    {
        return Excel::download(new FormationsExport, 'formations.xlsx');
    }

    public function render()
    {
        return $this->liste_formation();
    }

    public function show($id)
    {
        $formation = Formations::with('contenusFormation')->find($id);

        if ($formation) {
            return response()->json(['formation' => $formation, 'contenus' => $formation->contenusFormation]);
        } else {
            return response()->json(['error' => 'Formation non trouvée'], 404);
        }
    }

    public function search1(Request $request)
    {
        if ($request->ajax()) {
            $search1 = $request->search1;
            $formations = Formations::where(function($query) use ($search1) {
                $query->where('id', 'like', "%$search1%")
                    ->orWhere('code', 'like', "%$search1%")
                    ->orWhere('nom', 'like', "%$search1%")
                    ->orWhere('duree', 'like', "%$search1%");
            })->paginate(4);

            $view = view('livewire.example-laravel.formations-list', compact('formations'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function getFormationContents($id)
    {
        $formation = Formations::with('contenusFormation')->find($id);

        if ($formation) {
            return response()->json(['contenus' => $formation->contenusFormation]);
        } else {
            return response()->json(['error' => 'Formation non trouvée'], 404);
        }
    }

    public function showContents($id)
    {
        $formation = Formations::with('contenusFormation')->find($id);
        if (!$formation) {
            return response()->json(['error' => 'Formation non trouvée'], 404);
        }

        return response()->json(['contenus' => $formation->contenusFormation]);
    }
    public function getContents($formationId)
{
    $contenus = ContenusFormation::where('formation_id', $formationId)->get();
    return response()->json(['contenus' => $contenus]);
}

}