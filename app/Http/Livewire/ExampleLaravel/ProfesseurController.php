<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use App\Models\Professeur;
use App\Models\Typeymntprofs;
use App\Models\Country;
use App\Models\Sessions;
use App\Models\PaiementProf;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProfesseurExport;
use App\Models\ModePaiement;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ProfesseurController extends Component
{
    public function liste_prof()
    {
        $profs = Professeur::with('sessions')->paginate(4);
        $countries = Country::all();
        $typeymntprofs = Typeymntprofs::all();
        $mode_paiement = ModePaiement::all();
        return view('livewire.example-laravel.prof-management', compact('profs', 'countries', 'typeymntprofs', 'mode_paiement'));
    }
    

    public function searchByPhone(Request $request)
    {
        $phone = $request->phone;
        $professeur = Professeur::where('phone', $phone)->first();

        if ($professeur) {
            return response()->json(['professeur' => $professeur]);
        } else {
            return response()->json(['error' => 'Professeur non trouvé'], 404);
        }
    }

    public function addProfToSession(Request $request, $sessionId)
    {
        $request->validate([
            'prof_id' => 'required|exists:professeurs,id',
            'montant_paye' => 'required|numeric',
            'mode_paiement' => 'required|exists:modes_paiement,id',
            'date_paiement' => 'required|date',
            'montant' => 'required|numeric',
            'montant_a_paye' => 'required|numeric',
            'typeymntprofs_id' => 'required|exists:typeymntprofs,id',
        ]);

        try {
            $session = Sessions::findOrFail($sessionId);
            $profId = $request->prof_id;

            // Attach the professor to the session with the payment date
            $session->professeurs()->attach($profId, [
                'date_paiement' => $request->date_paiement,
            ]);

            // Create a new PaiementProf record
            $paiementProf = new PaiementProf([
                'prof_id' => $profId,
                'session_id' => $sessionId,
                'montant' => $request->montant,
                'montant_a_paye' => $request->montant_a_paye,
                'montant_paye' => $request->montant_paye,
                'mode_paiement_id' => $request->mode_paiement,
                'date_paiement' => $request->date_paiement,
                'typeymntprofs_id' => $request->typeymntprofs_id,
            ]);
            $paiementProf->save();

            return response()->json(['success' => 'Professeur et paiement ajoutés avec succès']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Session non trouvée.'], 404);
        } catch (\Exception $e) {
            Log::error('Error adding professor to session: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'ajout du professeur et du paiement: ' . $e->getMessage()], 500);
        }
    }

    public function getProfSessionContents($sessionId)
    {
        $session = Sessions::with(['professeurs' => function($query) {
            $query->withPivot('date_paiement');
        }, 'professeurs.paiements.mode', 'formation'])->find($sessionId);

        if (!$session) {
            return response()->json(['error' => 'Session non trouvée'], 404);
        }

        $professeurs = $session->professeurs->map(function($professeur) use ($session) {
            $montantPaye = $professeur->paiements->where('session_id', $session->id)->sum('montant_paye');
            $montant = $professeur->paiements->where('session_id', $session->id)->first()->montant ?? 0;
            $resteAPayer = $montant - $montantPaye;

            return [
                'id' => $professeur->id,
                'nomprenom' => $professeur->nomprenom,
                'phone' => $professeur->phone,
                'wtsp' => $professeur->wtsp,
                'montant' => $montant,
                'montant_paye' => $montantPaye,
                'reste_a_payer' => $resteAPayer,
                'mode_paiement' => $professeur->paiements->where('session_id', $session->id)->first()->mode->nom ?? '',
                'date_paiement' => $professeur->paiements->where('session_id', $session->id)->first()->date_paiement ?? '',
            ];
        });

        return response()->json([
            'professeurs' => $professeurs,
            'formation_price' => $session->formation->prix,
        ]);
    }

    public function addPaiement(Request $request, $sessionId)
    {
        Log::info('Received data:', $request->all()); // Log the received data
    
        $request->validate([
            'prof_id' => 'required|exists:professeurs,id',
            'montant_paye' => 'required|numeric',
            'mode_paiement' => 'required|exists:modes_paiement,id',
            'date_paiement' => 'required|date',
        ]);
    
        try {
            $professeur = Professeur::findOrFail($request->prof_id);
            $session = Sessions::findOrFail($sessionId);
    
            $paiement = new PaiementProf([
                'prof_id' => $request->prof_id,
                'session_id' => $sessionId,
                'montant' => $request->montant,
                'montant_a_paye' => $request->montant_a_paye,
                'montant_paye' => $request->montant_paye,
                'mode_paiement_id' => $request->mode_paiement,
                'date_paiement' => $request->date_paiement,
                'typeymntprofs_id' => $request->typeymntprofs_id,
            ]);
            $paiement->save();
    
            return response()->json(['success' => 'Paiement ajouté avec succès']);
        } catch (ModelNotFoundException $e) {
            Log::error('Model not found: ' . $e->getMessage());
            return response()->json(['error' => 'Session ou Professeur non trouvé.'], 404);
        } catch (\Exception $e) {
            Log::error('Error adding payment: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'ajout du paiement: ' . $e->getMessage()], 500);
        }
    }

public function deleteProfFromSession($sessionId, $profId)
    {
        try {
            $session = Sessions::findOrFail($sessionId);
            $session->professeurs()->detach($profId);

            PaiementProf::where('session_id', $sessionId)->where('prof_id', $profId)->delete();

            return response()->json(['success' => 'Professeur retiré de la session avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression du professeur: ' . $e->getMessage()], 500);
        }
    }

public function checkProfInSession(Request $request, $sessionId)
    {
        $profId = $request->prof_id;
        $session = Sessions::with('professeurs')->findOrFail($sessionId);

        $isInSession = $session->professeurs->contains($profId);

        return response()->json(['isInSession' => $isInSession]);
    }

    // public function searchByPhoneProf(Request $request)
    // {
    //     $phone = $request->phone;
    //     $prof = Professeur::where('phone', $phone)->first();

    //     if ($prof) {
    //         return response()->json(['prof' => $prof]);
    //     } else {
    //         return response()->json(['error' => 'Professeur non trouvé'], 404);
    //     }
    // }

    // public function addProfToSession(Request $request, $sessionId)
    // {
    //     $profId = $request->prof_id;
    //     $session = Sessions::find($sessionId);

    //     if ($session) {
    //         $session->etudiants()->attach($profId);
    //         return response()->json(['success' => 'Professeur ajouté à la Formation avec succès']);
    //     } else {
    //         return response()->json(['error' => 'Formation non trouvée'], 404);
    //     }
    // }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nomprenom' => 'required|string',
            'diplome' => 'nullable|string',
            'genre' => 'required|string',
            'lieunaissance' => 'nullable|string',
            'adress' => 'nullable|string',
            'datenaissance' => 'nullable|date',
            'email' => 'nullable|email',
            'phone' => 'required|digits:8|integer|gt:0',
            'wtsp' => 'nullable|integer',
            'country_id' => 'required|exists:countries,id',
            'type_id' => 'required|exists:typeymntprofs,id',
        ]);

        try {
            $imageName = $request->hasFile('image') ? time() . '.' . $request->image->extension() : null;
        
            if ($imageName) {
                $request->image->move(public_path('images'), $imageName);
            }
        
            $prof = Professeur::create([
                'image' => $imageName,
                'nomprenom' => $request->nomprenom,
                'diplome' => $request->diplome,
                'genre' => $request->genre,
                'lieunaissance' => $request->lieunaissance,
                'adress' => $request->adress,
                'datenaissance' => $request->datenaissance,
                'email' => $request->email,
                'phone' => $request->phone,
                'wtsp' => $request->wtsp,
                'country_id' => $request->country_id,
                'type_id' => $request->type_id,
            ]);
        
            return response()->json(['success' => 'Professeur créé avec succès', 'prof' => $prof->load('country', 'type')]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Throwable $th) {
            Log::error('Error creating prof: ', ['error' => $th->getMessage()]);
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nomprenom' => 'required|string',
            'diplome' => 'nullable|string',
            'genre' => 'required|string',
            'lieunaissance' => 'nullable|string',
            'adress' => 'nullable|string',
            'datenaissance' => 'nullable|date',
            'email' => 'nullable|email',
            'phone' => 'required|digits:8|integer|gt:0',
            'wtsp' => 'nullable|integer',
            'country_id' => 'required|exists:countries,id',
            'type_id' => 'required|exists:typeymntprofs,id',
        ]);

        try {
            $prof = Professeur::findOrFail($id);

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('images'), $imageName);
                $validated['image'] = $imageName;
            }

            $prof->update($validated);

            return response()->json(['success' => 'Professeur modifié avec succès', 'prof' => $prof->load('country', 'type')]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function delete_prof($id)
    {
        $prof = Professeur::findOrFail($id);
        if ($prof->sessions()->count() > 0) {
            return response()->json(['error' => 'Impossible de supprimer ce professeur, il est assigné à une ou plusieurs sessions.'], 422);
        }
        $prof->delete();

        return response()->json(['success' => 'Professeur supprimé avec succès']);
    }

    public function search4(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $profs = Professeur::where(function($query) use ($search) {
                $query->where('id', 'like', "%$search%")
                    ->orWhere('nomprenom', 'like', "%$search%")
                    ->orWhere('diplome', 'like', "%$search%")
                    ->orWhere('genre', 'like', "%$search%")
                    ->orWhere('lieunaissance', 'like', "%$search%")
                    ->orWhere('adress', 'like', "%$search%")
                    ->orWhere('datenaissance', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('wtsp', 'like', "%$search%");
            })->paginate(4);

            $view = view('livewire.example-laravel.professeur-list', compact('profs'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function export()
    {
        return Excel::download(new ProfesseurExport, 'Professeurs.xlsx');
    }

    public function render()
    {
        $profs = Professeur::paginate(4);
        $countries = Country::all();
        $typeymntprofs = Typeymntprofs::all();
        return view('livewire.example-laravel.prof-management', compact('profs', 'countries', 'typeymntprofs'));
    }
}
