<?php

use App\Http\Livewire\Auth\ForgotPassword;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\ResetPassword;
// use App\Http\Livewire\Billing;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\ExampleLaravel\UserManagement;
use App\Http\Livewire\ExampleLaravel\UserProfile;


use App\Http\Controllers\SearchetudController;

use App\Http\Livewire\ExampleLaravel\EtudiantController;
use App\Http\Livewire\ExampleLaravel\ProfesseurController;
use App\Http\Livewire\ExampleLaravel\FormationsController;
use App\Http\Livewire\ExampleLaravel\ContenusFormationController;
use App\Http\Livewire\ExampleLaravel\SessionsController;
use App\Http\Livewire\ExampleLaravel\PaiementController;




use App\Http\Controllers\ExportController;


// use App\Http\Livewire\Notifications;
use App\Http\Livewire\Profile;
// use App\Http\Livewire\RTL;
use App\Http\Livewire\StaticSignIn;
use App\Http\Livewire\StaticSignUp;
// use App\Http\Livewire\Tables;
// use App\Http\Livewire\VirtualReality;
use GuzzleHttp\Middleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
    return redirect('sign-in');
});

// Add professor to session
// Route::post('/sessions/{sessionId}/profs', [SessionsController::class, 'addProfToSession'])->name('sessions.addProfToSession');

// // Add payment for professor
// Route::post('/sessions/{sessionId}/paiements_prof', [SessionsController::class, 'addProfPaiement'])->name('sessions.addProfPaiement');

// // Show professors in a session
// Route::get('/sessions/{sessionId}/profs', [SessionsController::class, 'showProfContents'])->name('sessions.showProfContents');

// // Delete professor from session
// Route::delete('/sessions/{sessionId}/profs/{profId}', [SessionsController::class, 'deleteProfFromSession'])->name('sessions.deleteProfFromSession');

// // Search professor by phone
// Route::get('/profs/search', [SessionsController::class, 'searchProfByPhone'])->name('profs.search');

// // Show session details
// Route::get('/sessions/{sessionId}/details', [SessionsController::class, 'getSessionDetails'])->name('sessions.details');

// Route::get('/profs/search', [ProfesseurController::class, 'searchByPhoneProf'])->name('profs.search');
// Route::post('/sessions/{sessionId}/profs', [SessionsController::class, 'addProfToSession'])->name('sessions.addProf');
// Route::post('/sessions/{sessionId}/paiements_prof', [SessionsController::class, 'addProfPaiement'])->name('sessions.addProfPaiement');
// Route::delete('/sessions/{sessionId}/profs/{profId}', [SessionsController::class, 'deleteProfFromSession'])->name('sessions.deleteProf');

Route::post('/professeur/search', [SessionsController::class, 'searchProfByPhone'])->name('professeur.search');
Route::post('/sessions/{sessionId}/check-prof', [SessionsController::class, 'checkProfInSession']);
Route::post('/sessions/{sessionId}/profs/{profId}/add', [SessionsController::class, 'addProfToSession']);
Route::get('/sessions/{sessionId}/profs/{profId}/details', [SessionsController::class, 'getProfSessionContents']);
Route::post('/sessions/{sessionId}/paiements', [SessionsController::class, 'addProfPaiement']);
Route::delete('/sessions/{sessionId}/profs/{profId}', [SessionsController::class, 'deleteProfFromSession']);
Route::get('/sessions/{sessionId}/profcontents', [SessionsController::class, 'getProfSessionContents']);



Route::get('forgot-password', ForgotPassword::class)->middleware('guest')->name('password.forgot');
Route::get('reset-password/{id}', ResetPassword::class)->middleware('signed')->name('reset-password');



Route::get('sign-up', Register::class)->middleware('guest')->name('register');
Route::get('sign-in', Login::class)->middleware('guest')->name('login');

Route::get('user-profile', UserProfile::class)->middleware('auth')->name('user-profile');
Route::get('user-management', UserManagement::class)->middleware('auth')->name('user-management');


Route::get('/sessions/{id}/profs', [SessionsController::class, 'getProfSessionContents']);
Route::post('/sessions/{sessionId}/profs', [SessionsController::class, 'addProfToSession']);
Route::get('/profs/search', [ProfesseurController::class, 'searchByPhoneProf']);


// Formations routes
Route::get('/formations', [FormationsController::class, 'liste_formation']);
Route::post('/formations/store', [FormationsController::class, 'store']);
Route::put('/formations/{id}/update', [FormationsController::class, 'update']);
Route::delete('/formations/{id}/delete', [FormationsController::class, 'delete_formation']);




// Etudiant routes
// Route::post('/etudiants/search', [EtudiantController::class, 'searchByPhone']);
Route::post('/etudiants/{etudiantId}/sessions/{sessionId}/add', [EtudiantController::class, 'addStudentToSession']);
// Route::post('etudiant/search', [EtudiantController::class, 'searchByPhone'])->name('etudiant.search');
// Route::post('/etudiant/search', [EtudiantController::class, 'searchByPhone'])->name('etudiant.search');
Route::post('/etudiant/search', [EtudiantController::class, 'searchByPhone'])->name('etudiant.search');
Route::post('/sessions/{sessionId}/check-student', [SessionsController::class, 'checkStudentInSession'])->name('sessions.check-student');
// Route::post('/etudiants/search', [EtudiantController::class, 'searchByPhone']);
Route::post('/sessions/{sessionId}/etudiants/{etudiantId}/add', [EtudiantController::class, 'addStudentToSession']);
// Route::post('/etudiant/search', [EtudiantController::class, 'searchByPhone']);


// Sessions routes

Route::prefix('sessions')->group(function () {
    Route::get('/', [SessionsController::class, 'list_session'])->name('sessions.list');
    Route::post('store', [SessionsController::class, 'store'])->name('session.store');
    Route::post('update/{id}', [SessionsController::class, 'update'])->name('session.update');
    Route::delete('delete/{id}', [SessionsController::class, 'destroy'])->name('session.delete');
    Route::get('details/{id}', [SessionsController::class, 'getFormationDetails'])->name('sessions.details');
    Route::post('etudiants/{etudiantId}/add', [SessionsController::class, 'addEtudiantToSession']);
    Route::delete('{sessionId}/etudiants/{etudiantId}', [SessionsController::class, 'deleteStudentFromSession']);
    Route::delete('/sessions/{sessionId}/profs/{profId}', [SessionsController::class, 'deleteProfContent']);

    Route::post('{sessionId}/paiements', [SessionsController::class, 'addPaiement']);
    Route::get('{sessionId}/contents', [SessionsController::class, 'getSessionContents']);
    Route::get('{sessionId}/etudiants/{etudiantId}/details', [SessionsController::class, 'getStudentDetails']);
    Route::post('{sessionId}/check-student', [SessionsController::class, 'checkStudentInSession']);
    // Route::post('{sessionId}/professeurs/add', [SessionsController::class, 'addProfToSession']);
    Route::post('/sessions/{sessionId}/profs', [SessionsController::class, 'addProfToSession']);
    Route::delete('/sessions/{sessionId}/profs/{profId}', [SessionsController::class, 'deleteProfContent']);

    Route::get('{sessionId}/professeurs', [SessionsController::class, 'getProfSessionContents']);
    Route::post('etudiant/search', [SessionsController::class, 'searchStudentByPhone'])->name('etudiant.search');
});

Route::delete('/sessions/{id}', [SessionsController::class, 'destroy'])->name('session.delete');
Route::post('/sessions/update/{id}', [SessionsController::class, 'update'])->name('session.update');
Route::put('update/{id}', [SessionsController::class, 'update'])->name('session.update');



// Route::get('/sessions', [SessionsController::class, 'list_session'])->name('sessions.list');
// Route::post('/sessions/store', [SessionsController::class, 'store'])->name('session.store');
// Route::put('/sessions/{id}/update', [SessionsController::class, 'update'])->name('session.update');
// Route::delete('/sessions/{id}/delete', [SessionsController::class, 'destroy'])->name('session.delete');
Route::post('/sessions/search', [SessionsController::class, 'search6'])->name('search6');
// Route::get('/sessions/{sessionId}/contents', [SessionsController::class, 'getSessionContents']);
// Route::post('/sessions/{sessionId}/profs', [SessionsController::class, 'addProfToSession']);
// Route::post('/etudiants/search', [SessionsController::class, 'searchStudentByPhone'])->name('etudiants.search');
// Route::get('/sessions/{sessionId}/details', [SessionsController::class, 'getFormationDetails'])->name('sessions.details');
// Route::get('/sessions/{sessionId}/etudiants/{etudiantId}/details', [SessionsController::class, 'getStudentDetails']);
// Route::get('/sessions/{sessionId}/contents', [SessionsController::class, 'getSessionContents']);
// // Route::post('/sessions/{sessionId}/paiements', [SessionsController::class, 'addPaiement'])->name('sessions.paiements.add');
// // Route::post('/sessions/{sessionId}/check-student', [SessionsController::class, 'checkStudentInSession'])->name('sessions.check-student');
Route::get('sessions-management', SessionsController::class)->middleware('auth')->name('sessions-management');
Route::post('/sessions/search', [SessionsController::class, 'search6'])->name('search6');

// Route::post('/sessions/{sessionId}/paiements', [SessionsController::class, 'addPaiement'])->name('sessions.addPaiement');
// Route::delete('/sessions/{sessionId}/etudiants/{etudiantId}', [SessionsController::class, 'removeStudentFromSession'])->name('sessions.removeStudent');
// // Paiement routes
// Route::post('/etudiants/{etudiantId}/sessions/{sessionId}/paiement', [SessionsController::class, 'addPayment']);

// Route::get('/paiements/etudiant/{etudiantId}', [PaiementController::class, 'getPaymentsByStudent']);
// Route::get('/paiements/session/{sessionId}', [PaiementController::class, 'getPaymentsBySession']);
// Route::delete('/paiements/{id}/delete', [PaiementController::class, 'deletePayment']);
// Route::put('/paiements/{id}/update', [PaiementController::class, 'updatePayment']);
// Route::get('/paiements', [PaiementController::class, 'listPayments']);
// Route::post('/paiements/search', [PaiementController::class, 'searchPayments']);


Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.list_paiement');
Route::get('paiement-management', PaiementController::class)->middleware('auth')->name('paiement-management');
Route::get('export-paiements', [PaiementController::class, 'exportPaiements'])->name('export.paiements');


Route::get('/search6', [SessionsController::class, 'search6'])->name('search6');



Route::get('/contenues', [ContenusFormationController::class, 'liste_contenue'])->name('contenue.list');
Route::post('/contenues', [ContenusFormationController::class, 'store'])->name('contenue.store');
Route::put('/contenues/{id}', [ContenusFormationController::class, 'update'])->name('contenue.update');
Route::delete('/contenues/{id}', [ContenusFormationController::class, 'delete_contenue'])->name('contenue.delete');
Route::get('/export/contenues', [ContenusFormationController::class, 'export'])->name('export.contenues');
Route::get('contenusformation-management', ContenusFormationController::class)->middleware('auth')->name('contenusformation-management');
Route::get('/search3', [ContenusFormationController::class, 'search3'])->name('search3');






//Etudiant routes
Route::get('/etudiants', [EtudiantController::class, 'liste_etudiant'])->name('etudiant.list');
Route::post('etudiants', [EtudiantController::class, 'store'])->name('etudiant.store');

Route::put('/etudiants/{id}', [EtudiantController::class, 'update'])->name('etudiant.update');
// Route::delete('/etudiants/{id}', [EtudiantController::class, 'delete_etudiant'])->name('etudiant.delete');
Route::get('/search', [EtudiantController::class, 'search'])->name('search');
Route::get('/export/etudiants', [EtudiantController::class, 'export'])->name('export.etudiants');
Route::get('etudiant-management', EtudiantController::class)->middleware('auth')->name('etudiant-management');
Route::get('/search', [EtudiantController::class, 'search'])->name('search');

Route::post('/etudiants', [EtudiantController::class, 'store'])->name('etudiant.store');

Route::get('etudiant/delete/{id}', [EtudiantController::class, 'deleteEtudiant'])->name('etudiant.delete');
Route::delete('etudiant/confirm_delete/{id}', [EtudiantController::class, 'confirmDeleteEtudiant'])->name('etudiant.confirm_delete');

// Route::post('/etudiants', [EtudiantController::class, 'store'])->name('etudiant.store');

// Route::post('/etudiants', [EtudiantController::class, 'store'])->name('etudiant.store');

// Professeur routes
Route::get('prof-management', ProfesseurController::class)->middleware('auth')->name('prof-management');
Route::get('/profs/export', [ProfesseurController::class, 'export'])->name('prof.export');
Route::post('/profs/add-to-session/{sessionId}', [ProfesseurController::class, 'addProfToSession'])->name('prof.add_to_session');
Route::get('prof/list', [ProfesseurController::class, 'liste_prof'])->name('prof.list');
Route::post('prof/store', [ProfesseurController::class, 'store'])->name('prof.store');
Route::put('prof/update/{id}', [ProfesseurController::class, 'update'])->name('prof.update'); // Changed to PUT method
Route::delete('prof/delete/{id}', [ProfesseurController::class, 'delete_prof'])->name('prof.delete');
Route::get('search4', [ProfesseurController::class, 'search4'])->name('search4');



Route::get('export/professeurs', [ExportController::class, 'exportProfesseurs'])->name('export.professeurs');
Route::get('export/etudiants', [ExportController::class, 'exportEtudiants'])->name('export.etudiants');
Route::get('export/formationa', [ExportController::class, 'formationsExport'])->name('formations.export');
Route::get('export/contenus', [ExportController::class, 'exportContenusFormation'])->name('contenues.export');
Route::get('export/sessions', [SessionsController::class, 'exportSessions'])->name('sessions.export');


Route::get('formations-management', FormationsController::class)->middleware('auth')->name('formations-management');
Route::put('formations/{id}', [FormationsController::class, 'update'])->middleware('auth')->name('formations.update');
Route::post('/formations', [FormationsController::class, 'store'])->middleware('auth')->name('formations.store');
// Route::get('/delete-formation/{id}',[FormationsController::class , 'delete_formation'])->middleware('auth')->name('formations.delete_etudiant');
Route::post('/formation/store', [FormationsController::class,'store'])->name('formation.store');
Route::get('/formations/{id}', [FormationsController::class, 'show'])->name('formations.show');
Route::get('/formations', [FormationsController::class, 'liste_formation'])->name('formations.liste');
Route::get('/search1', [FormationsController::class, 'search1'])->name('search1');
// Route::delete('/formations/confirm-delete/{id}', [FormationsController::class, 'confirm_delete_formation']);
// Route::delete('/formations/{id}', [FormationsController::class, 'delete_formation']);
Route::get('formations/{id}', [FormationsController::class, 'show']);
Route::put('formations/{id}', [FormationsController::class, 'update']);
Route::delete('formations/{id}', [FormationsController::class, 'delete_formation']);
// Route::delete('formations/confirm-delete/{id}', [FormationsController::class, 'confirm_delete_formation']);
Route::get('formations/{id}/contents', [FormationsController::class, 'getFormationContents']);
Route::delete('/formations/{id}/delete', [FormationsController::class, 'deleteFormation'])->name('formations.delete');
Route::delete('/formations/{id}/confirm-delete', [FormationsController::class, 'confirmDeleteFormation'])->name('formations.confirm-delete');







Route::get('contenus', [ContenusFormationController::class, 'liste_contenue'])->name('contenus-management');
Route::post('contenus/store', [ContenusFormationController::class, 'store'])->name('contenus.store');
Route::get('contenus/{id}', [ContenusFormationController::class, 'show']);
Route::post('contenus/{id}', [ContenusFormationController::class, 'update']);
Route::delete('contenus/{id}', [ContenusFormationController::class, 'delete_contenue']);
Route::get('/contenus', [ContenusFormationController::class, 'liste_contenus'])->name('contennus-management');
Route::get('contennus-management', ContenusFormationController::class)->middleware('auth')->name('contenuus-management');
Route::post('/contenu/store', [ContenusFormationController::class,'store'])->name('contenu.store');
Route::get('/contenus/{id}', [FormationsController::class, 'show'])->name('contenus.show');
Route::get('/contenus', [FormationsController::class, 'liste_contenus'])->name('contenus.liste');


Route::put('/contenus/{id}', [ContenusFormationController::class, 'update'])->name('contenus.update');


Route::group(['middleware' => 'auth'], function () {
Route::get('dashboard', Dashboard::class)->name('dashboard');
// Route::get('billing', Billing::class)->name('billing');
Route::get('profile', Profile::class)->name('profile');
// Route::get('tables', Tables::class)->name('tables');
// Route::get('notifications', Notifications::class)->name("notifications");
// Route::get('virtual-reality', VirtualReality::class)->name('virtual-reality');
Route::get('static-sign-in', StaticSignIn::class)->name('static-sign-in');
Route::get('static-sign-up', StaticSignUp::class)->name('static-sign-up');
// Route::get('rtl', RTL::class)->name('rtl');
});