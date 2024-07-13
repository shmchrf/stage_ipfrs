<?php


namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;

use App\Models\Paiement;
use App\Models\Etudiant;
use App\Models\Sessions;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaiementsExport;


class PaiementController extends Component
{



    public function list_paiement()
    {
        $paiements = Paiement::with(['etudiant', 'session.formation', 'mode'])
            ->join('etudiants', 'paiements.etudiant_id', '=', 'etudiants.id')
            ->join('sessions', 'paiements.session_id', '=', 'sessions.id')
            ->orderBy('sessions.nom', 'asc')
            ->orderBy('etudiants.nomprenom', 'asc')
            ->select('paiements.*')
            ->paginate(8);
    
        // Calculer le reste à payer
        foreach ($paiements as $paiement) {
            $montantPayeTotal = Paiement::where('etudiant_id', $paiement->etudiant_id)
                ->where('session_id', $paiement->session_id)
                ->sum('montant_paye');
            $paiement->reste_a_payer = $paiement->prix_reel - $montantPayeTotal;
        }
    
        return view('livewire.example-laravel.paiement-management', compact('paiements'));
    }
    


    public function render(){
        return $this->list_paiement();
    }

    public function exportPaiements()
    {
        return Excel::download(new PaiementsExport, 'paiements.xlsx');
    }



    public function searchPayments(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $paiements = Paiement::with('etudiant', 'session')
                ->where('montant_paye', 'like', "%$search%")
                ->orWhereHas('etudiant', function($query) use ($search) {
                    $query->where('nomprenom', 'like', "%$search%");
                })
                ->orWhereHas('session', function($query) use ($search) {
                    $query->where('nom', 'like', "%$search%");
                })
                ->paginate(10);

            $view = view('livewire.example-laravel.payment-list', compact('paiements'))->render();
            return response()->json(['html' => $view]);
        }
    }
}
