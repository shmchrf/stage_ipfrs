<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Formations Management</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .imgUpload {
            max-width: 90px;
            max-height: 70px;
            min-width: 50px;
            min-height: 50px;
        }
        .required::after {
            content: " *";
            color: red;
        }
        .form-control {
            border: 1px solid #ccc;
        }
        .form-control:focus {
            border-color: #66afe9;
            outline: 0;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
        }
    </style>
</head>
<body>

<!-- Add Student Modal -->
<div class="modal fade" id="etudiantAddModal" tabindex="-1" aria-labelledby="etudiantAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="etudiantAddModalLabel">Ajouter un étudiant à la formation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="new-student-session_id">
                <div class="form-group">
                    <label for="student-phone-search" class="form-label">Numéro de téléphone de l'étudiant:</label>
                    <input type="text" class="form-control" id="student-phone-search" placeholder="Entrez le numéro de téléphone">
                </div>
                <button type="button" class="btn btn-primary" onclick="searchStudentByPhone()">Rechercher</button>
                <div id="student-search-results"></div>
                <div id="payment-form" style="display:none;">
                    <div class="row mb-3">
                        <div class="form-group col-md-4">
                            <label for="formation-price" class="form-label">Prix Programme:</label>
                            <input type="text" class="form-control" id="formation-price" readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="prix-reel" class="form-label">Prix Réel:</label>
                            <input type="text" class="form-control" id="prix-reel" placeholder="Entrez le prix réel">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="montant-paye" class="form-label">Montant Payé:</label>
                            <input type="text" class="form-control" id="montant-paye" placeholder="Entrez le montant payé">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="form-group col-md-6">
                            <label for="mode-paiement" class="form-label">Mode de Paiement:</label>
                            <select class="form-control" id="mode-paiement">
                                @foreach ($modes_paiement as $mode)
                                    <option value="{{ $mode->id }}">{{ $mode->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="date-paiement" class="form-label">Date de Paiement:</label>
                            <input type="date" class="form-control" id="date-paiement">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" onclick="addEtudiantAndPaiement()">Ajouter Etudiant et Paiement</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="paiementAddModal" tabindex="-1" aria-labelledby="paiementAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paiementAddModalLabel">Ajouter un Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="etudiant-id">
                <input type="hidden" id="session-id">
                <input type="hidden" id="prix-formation">
                <input type="hidden" id="prix-reel">
                <input type="hidden" id="reste-a-payer">
                <div class="form-group">
                    <label for="nouveau-montant-paye" class="form-label">Nouveau Montant Payé:</label>
                    <input type="text" class="form-control" id="nouveau-montant-paye" placeholder="Entrez le montant payé">
                </div>
                <div class="form-group">
                    <label for="mode-paiement" class="form-label">Mode de Paiement:</label>
                    <select class="form-control" id="mode-paiement">
                        @foreach ($modes_paiement as $mode)
                            <option value="{{ $mode->id }}">{{ $mode->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="date-paiement" class="form-label">Date de Paiement:</label>
                    <input type="date" class="form-control" id="date-paiement" name="date_paiement">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" onclick="addPaiement()">Ajouter Paiement</button>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Professor Modal -->
<div class="modal fade" id="profAddModal" tabindex="-1" aria-labelledby="profAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profAddModalLabel">Ajouter un professeur à la session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="new-prof-session_id">
                <div class="form-group">
                    <label for="prof-phone-search" class="form-label">Numéro de téléphone du professeur:</label>
                    <input type="text" class="form-control" id="prof-phone-search" placeholder="Entrez le numéro de téléphone">
                </div>
                <button type="button" class="btn btn-primary" onclick="searchProfByPhone()">Rechercher</button>
                <div id="prof-search-results"></div>
                <div id="prof-payment-form" style="display:none;">
                    <div class="row mb-3">
                        <div class="form-group col-md-4">
                            <label for="prof-typeymntprofs" class="form-label">Type de Paiement:</label>
                            <select class="form-control" id="prof-typeymntprofs" onchange="updatePaymentFields()">
                                <option value="">Sélectionner un type de paiement</option>
                                @foreach ($typeymntprofs as $type)
                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="prof-montant" class="form-label">Montant:</label>
                            <input type="text" class="form-control" id="prof-montant" placeholder="Entrez le montant">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="prof-montant_a_paye" class="form-label">Montant à Payer:</label>
                            <input type="text" class="form-control" id="prof-montant_a_paye" placeholder="Entrez le montant à payer">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="form-group col-md-4">
                            <label for="prof-montant_paye" class="form-label">Montant Payé:</label>
                            <input type="text" class="form-control" id="prof-montant_paye" placeholder="Entrez le montant payé">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="prof-mode-paiement" class="form-label">Mode de Paiement:</label>
                            <select class="form-control" id="prof-mode-paiement">
                                @foreach ($modes_paiement as $mode)
                                    <option value="{{ $mode->id }}">{{ $mode->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="prof-date-paiement" class="form-label">Date de Paiement:</label>
                            <input type="date" class="form-control" id="prof-date-paiement">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" onclick="addProfAndPaiement()">Ajouter Professeur et Paiement</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="profPaiementAddModal" tabindex="-1" aria-labelledby="profPaiementAddModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profPaiementAddModalLabel">Ajouter un Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="prof-id">
                <input type="hidden" id="prof-session-id">
                <input type="hidden" id="prof-montant">
                <input type="hidden" id="prof-montant_a_paye">
                <input type="hidden" id="prof-reste-a-payer">
                <div class="form-group">
                    <label for="prof-nouveau-montant-paye" class="form-label">Nouveau Montant Payé:</label>
                    <input type="text" class="form-control" id="prof-nouveau-montant-paye" placeholder="Entrez le montant payé">
                </div>
                <div class="form-group">
                    <label for="prof-mode-paiement" class="form-label">Mode de Paiement:</label>
                    <select class="form-control" id="prof-mode-paiement">
                        @foreach ($modes_paiement as $mode)
                            <option value="{{ $mode->id }}">{{ $mode->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="prof-date-paiement" class="form-label">Date de Paiement:</label>
                    <input type="date" class="form-control" id="prof-date-paiement" name="date_paiement">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" onclick="addProfPaiement()">Ajouter Paiement</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>






<div id="formationContentContainer" style="display:none;">
    <center><h4>Liste des étudiants</h4></center>
    <div id="formationContents"></div>
</div>

<div id="formationProfContentContainer" style="display:none;">
    <center><h4>Liste des Professeurs</h4></center>
    <div id="formationProfContents"></div>
</div>


<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            @if (session('status'))
            <div class="alert alert-success fade-out">
                {{ session('status')}}
            </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
            @endif
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                    <div>
                        <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#sessionAddModal">
                            <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter 
                        </button>
                        <a href="{{ route('sessions.export') }}" class="btn btn-success">Exporter </a>
                    </div>
                    <form class="d-flex align-items-center ms-auto">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search6" id="search_bar" class="form-control" placeholder="Rechercher..." value="{{ isset($search6) ? $search6 : '' }}">
                        </div>
                    </form>
                                    </div>
                                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0" id="sessions-table">
                            @include('livewire.example-laravel.sessions-list', ['sessions' => $sessions])
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>



<!-- Add Session Modal -->
<div class="modal fade" id="sessionAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter une nouvelle Formation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="session-add-form">
                    @csrf
                    <div class="row mb-2">
                        <div class="form-group col-md-6">
                            <label for="formation_id" class="form-label required">Programme</label>
                            <select class="form-control" id="new-session-formation_id" name="formation_id">
                                <option value="">Sélectionner Programme</option>
                                @foreach ($formations as $formation)
                                    <option value="{{ $formation->id }}">{{ $formation->nom }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="formation_id-warning"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom:</label>
                            <input type="text" class="form-control" id="new-session-nom" placeholder="nom" name="nom">
                            <div class="text-danger" id="nom-warning"></div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="date_debut" class="form-label required">Date début:</label>
                            <input type="date" class="form-control" id="new-session-date_debut" name="date_debut">
                            <div class="text-danger" id="date_debut-warning"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="date_fin" class="form-label required">Date fin:</label>
                            <input type="date" class="form-control" id="new-session-date_fin" name="date_fin">
                            <div class="text-danger" id="date_fin-warning"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="add-new-session">Ajouter</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Session Modal -->
<div class="modal fade" id="sessionEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier Formation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="session-edit-form">
                    @csrf
                    <input type="hidden" id="session-id" name="id">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="formation_id" class="form-label required">Programme</label>
                            <select class="form-control" id="session-formation_id" name="formation_id">
                                <option value="">Sélectionner Programme</option>
                                @foreach ($formations as $formation)
                                    <option value="{{ $formation->id }}">{{ $formation->nom }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="edit-formation_id-warning"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label required">Nom :</label>
                            <input type="text" class="form-control" id="session-nom" name="nom">
                            <div class="text-danger" id="edit-nom-warning"></div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="date_debut" class="form-label required">Date début :</label>
                            <input type="date" class="form-control" id="session-date_debut" name="date_debut">
                            <div class="text-danger" id="edit-date_debut-warning"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="date_fin" class="form-label required">Date fin:</label>
                            <input type="date" class="form-control" id="session-date_fin" name="date_fin">
                            <div class="text-danger" id="edit-date_fin-warning"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="session-update">Modifier</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function getSessionDates(sessionId) {
        return $.ajax({
            url: `/sessions/${sessionId}/dates`,
            type: 'GET'
        });
    }

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('#search_bar').on('keyup', function(){
        var query = $(this).val();
        $.ajax({
            url: "{{ route('search6') }}",
            type: "GET",
            data: {'search6': query},
            success: function(data){
                $('#sessions-table').html(data.html);
            }
        });
    });

    $("#add-new-session").click(function(e){
        e.preventDefault();
        let form = $('#session-add-form')[0];
        let data = new FormData(form);

        $.ajax({
            url: "{{ route('session.store') }}",
            type: "POST",
            data: data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.errors) {
                    var errorMsg = '';
                    $.each(response.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            errorMsg += error + '<br>';
                        });
                    });
                    iziToast.error({
                        message: errorMsg,
                        position: 'topRight'
                    });
                } else {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                    $('#sessionAddModal').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errorMsg = '';
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            errorMsg += error + '<br>';
                        });
                    });
                    iziToast.error({
                        message: errorMsg,
                        position: 'topRight'
                    });
                } else {
                    iziToast.error({
                        message: 'An error occurred: ' + error,
                        position: 'topRight'
                    });
                }
            }
        });
    });

    $('body').on('click', '#edit-session', function () {
        var tr = $(this).closest('tr');
        $('#session-id').val(tr.find("td:nth-child(1)").text());
        $('#session-formation_id').val(tr.find("td:nth-child(2)").data('formation-id'));
        $('#session-nom').val(tr.find("td:nth-child(3)").text());
        $('#session-date_debut').val(tr.find("td:nth-child(4)").text());
        $('#session-date_fin').val(tr.find("td:nth-child(5)").text());

        $('#sessionEditModal').modal('show');
    });

    $('body').on('click', '#session-update', function () {
        var id = $('#session-id').val();
        var formData = new FormData($('#session-edit-form')[0]);

        $.ajax({
            url: "{{ route('session.update', '') }}/" + id,
            type: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                $('#sessionEditModal').modal('hide');
                setTimeout(function () {
                    location.reload();
                }, 1000);
                if (response.success) {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                } else {
                    iziToast.error({
                        message: response.error,
                        position: 'topRight',
                    });
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(key, value) {
                        $('#' + key + '-warning').text(value[0]);
                    });
                }
            }
        });
    });

    $('body').on('click', '#delete-session', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: "{{ route('session.delete', '') }}/" + id,
            type: 'DELETE',
            success: function(response) {
                if (response.status === 400) {
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    message: 'An error occurred: ' + error,
                    position: 'topRight'
                });
            }
        });
    });

    window.setSessionId = function(sessionId) {
        $('#new-student-session_id').val(sessionId);
    }

    window.searchStudentByPhone = function() {
        const phone = $('#student-phone-search').val();
        const sessionId = $('#new-student-session_id').val();

        if (phone) {
            $.ajax({
                url: '{{ route("etudiant.search") }}',
                type: 'POST',
                data: { phone: phone },
                success: function(response) {
                    if (response.etudiant) {
                        const etudiant = response.etudiant;
                        $.ajax({
                            url: `/sessions/${sessionId}/check-student`,
                            type: 'POST',
                            data: { etudiant_id: etudiant.id },
                            success: function(checkResponse) {
                                if (checkResponse.isInSession) {
                                    $('#student-search-results').html('<div class="alert alert-danger">L\'étudiant est déjà inscrit dans cette Formation.</div>');
                                } else {
                                    $('#student-search-results').html(
                                        `<div class="alert alert-success">Etudiant trouvé: ${etudiant.nomprenom}</div>
                                        <input type="hidden" id="etudiant-id" value="${etudiant.id}">`
                                    );
                                    loadFormationDetails();
                                    $('#payment-form').show();
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Erreur lors de la vérification de l\'étudiant dans la Formation: ' + error);
                            }
                        });
                    } else {
                        $('#student-search-results').html('<div class="alert alert-danger">Etudiant non trouvé.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erreur lors de la recherche de l\'étudiant: ' + error);
                }
            });
        } else {
            alert('Veuillez entrer un numéro de téléphone.');
        }
    }

    window.loadFormationDetails = function() {
        const sessionId = $('#new-student-session_id').val();
        $.ajax({
            url: "{{ route('sessions.details', ':sessionId') }}".replace(':sessionId', sessionId),
            type: 'GET',
            success: function(response) {
                if (response.formation) {
                    $('#formation-price').val(response.formation.prix);
                    $('#prix-reel').val(response.formation.prix); // Set Prix Réel to Prix Programme
                    const today = new Date().toISOString().split('T')[0];
                    $('#date-paiement').val(today); // Set Date de Paiement to today's date
                } else {
                    $('#formation-price').val('');
                    $('#prix-reel').val(''); // Clear Prix Réel if no formation data is found
                }
            },
            error: function(xhr, status, error) {
                alert('Erreur lors du chargement des détails de la formation: ' + error);
            }
        });
    }

    window.addEtudiantAndPaiement = function() {
        const etudiantId = $('#etudiant-id').val();
        const sessionId = $('#new-student-session_id').val();
        const datePaiement = $('#date-paiement').val();
        const montantPaye = $('#montant-paye').val();
        const modePaiement = $('#mode-paiement').val();
        const prixReel = $('#prix-reel').val();

        if (!etudiantId || !sessionId) {
            alert('Etudiant ID or Session ID is missing.');
            return;
        }

        $.ajax({
            url: `/sessions/${sessionId}/etudiants/${etudiantId}/add`,
            type: 'POST',
            data: {
                etudiant_id: etudiantId,
                date_paiement: datePaiement,
                montant_paye: montantPaye,
                mode_paiement: modePaiement,
                prix_reel: prixReel
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#etudiantAddModal').modal('hide');
                    showContents(sessionId);
                } else {
                    alert(response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                alert('Erreur lors de l\'ajout de l\'étudiant: ' + xhr.responseText);
            }
        });
    }

    window.openAddPaymentModal = function(etudiantId, sessionId) {
        $.ajax({
            url: `/sessions/${sessionId}/etudiants/${etudiantId}/details`,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const resteAPayer = response.reste_a_payer;
                    if (resteAPayer <= 0) {
                        iziToast.warning({
                            message: 'L\'étudiant a déjà payé la totalité de la formation.',
                            position: 'topRight'
                        });
                    } else {
                        $('#etudiant-id').val(etudiantId);
                        $('#session-id').val(sessionId);
                        $('#prix-formation').val(response.prix_formation);
                        $('#prix-reel').val(response.prix_reel);
                        $('#reste-a-payer').val(resteAPayer);
                        $('#paiementAddModal').modal('show');
                    }
                } else {
                    iziToast.error({
                        message: response.error,
                        position: 'topRight'
                    });
                }
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    message: 'Erreur lors de la récupération des détails: ' + error,
                    position: 'topRight'
                });
            }
        });
    };




window.addPaiement = function() {
    let etudiantId = $('#etudiant-id').val();
    let sessionId = $('#session-id').val();
    let nouveauMontantPaye = $('#nouveau-montant-paye').val();
    let modePaiement = $('#mode-paiement').val();
    let datePaiement = $('#date-paiement').val();

    $.ajax({
        url: `/sessions/${sessionId}/paiements`,
        type: 'POST',
        data: {
            etudiant_id: etudiantId,
            montant_paye: nouveauMontantPaye,
            mode_paiement: modePaiement,
            date_paiement: datePaiement  // Ensure this field is included
        },
        success: function(response) {
            if (response.success) {
                $('#paiementAddModal').modal('hide');
                showContents(sessionId);
            } else {
                alert(response.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
            alert('Erreur lors de l\'ajout du paiement: ' + xhr.responseText);
        }
    });
};



window.addProfPaiement = function() {
    let profId = $('#prof-id').val();
    let sessionId = $('#prof-session-id').val();
    let montantPaye = Math.round(parseFloat($('#prof-nouveau-montant-paye').val()));
    let modePaiement = $('#prof-mode-paiement').val();
    let datePaiement = $('#prof-date-paiement').val();

    // Debug log to ensure the date field value is retrieved correctly
    console.log({
        profId: profId,
        sessionId: sessionId,
        montantPaye: montantPaye,
        modePaiement: modePaiement,
        datePaiement: datePaiement // Log the date value
    });

    // Ensure the date is formatted correctly as 'YYYY-MM-DD'
    if (!datePaiement) {
        alert('Veuillez sélectionner une date de paiement.');
        return;
    }

    $.ajax({
        url: `/sessions/${sessionId}/profpaiements`,
        type: 'POST',
        data: {
            prof_id: profId,
            montant_paye: montantPaye,
            mode_paiement: modePaiement,
            date_paiement: datePaiement
        },
        success: function(response) {
            if (response.success) {
                $('#profPaiementAddModal').modal('hide');
                showProfContents(sessionId);
            } else {
                alert(response.error);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            console.error('Status:', status);
            console.error('Response:', xhr.responseText);
            alert('Erreur lors de l\'ajout du paiement: ' + xhr.responseText);
        }
    });
};








    window.deleteStudentFromSession = function(etudiantId, sessionId) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cet étudiant de la Formation ?")) {
            $.ajax({
                url: `/sessions/${sessionId}/etudiants/${etudiantId}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        iziToast.success({
                            message: response.success,
                            position: 'topRight'
                        });
                        showContents(sessionId);
                    } else {
                        iziToast.error({
                            message: response.error,
                            position: 'topRight'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        message: 'Erreur lors de la suppression: ' + error,
                        position: 'topRight'
                    });
                }
            });
        }
    };

    window.showContents = function(sessionId) {
        $.ajax({
            url: `/sessions/${sessionId}/contents`,
            type: 'GET',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }

                let html = `<div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card my-4">
                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#etudiantAddModal" onclick="setSessionId(${sessionId})" data-toggle="tooltip" title="Ajouter un étudiant"><i class="material-icons opacity-10">add</i></button>
                                        <button class="btn btn-secondary" onclick="hideStudentContents()">Fermer</button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-2">
                                    <div class="table-responsive p-0" id="sessions-table">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Nom & Prénom</th>
                                                    <th>Phone</th>
                                                    <th>WhatsApp</th>
                                                    <th>Prix Programme</th>
                                                    <th>Prix Réel</th>
                                                    <th>Montant Payé</th>
                                                    <th>Reste à Payer</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

                if (response.etudiants.length > 0) {
                    response.etudiants.forEach(function(content) {
                        let resteAPayer = content.prix_reel - content.montant_paye;

                        html += `<tr>
                            <td>${content.nomprenom}</td>
                            <td>${content.phone}</td>
                            <td>${content.wtsp}</td>
                            <td>${content.prix_formation}</td>
                            <td>${content.prix_reel}</td>
                            <td>${content.montant_paye}</td>
                            <td>${resteAPayer}</td>
                            <td>
                                <button class="btn btn-dark" onclick="openAddPaymentModal(${content.id}, ${sessionId})"><i class="material-icons opacity-10">payment</i></button>
                                <button class="btn btn-danger" onclick="deleteStudentFromSession(${content.id}, ${sessionId})"><i class="material-icons opacity-10">delete_forever</i></button>
                                 @foreach($sessions as $session)
    @foreach($session->etudiants as $etudiant)
    
            {{ $etudiant->nom }}</td>
            
                <a href="{{ route('sessions.generateReceipt', ['etudiantId' => $etudiant->id, 'sessionId' => $session->id]) }}" class="btn btn-info">
                    <i class="material-icons opacity-10">download</i>
                </a>
        
        
    @endforeach
@endforeach

</td>
                            </td>
                        </tr>`;
                    });
                } else {
                    html += '<tr><td colspan="8" class="text-center">Aucun étudiant trouvé pour cette Formation.</td></tr>';
                }

                html += `</tbody></table></div></div></div></div></div>`;
                $('#formationContents').html(html);
                $('#formationContentContainer').show();
                $('html, body').animate({ scrollTop: $('#formationContentContainer').offset().top }, 'slow');
            },
            error: function() {
                alert('Erreur lors du chargement des contenus.');
            }
        });
    };

    window.hideStudentContents = function() {
        $('#formationContentContainer').hide();
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    };

    window.setProfSessionId = function(sessionId) {
        $('#new-prof-session_id').val(sessionId);
    }

    window.searchProfByPhone = function() {
        const phone = $('#prof-phone-search').val();
        const sessionId = $('#new-prof-session_id').val();

        if (phone) {
            $.ajax({
                url: '{{ route("professeur.search") }}',
                type: 'POST',
                data: { phone: phone },
                success: function(response) {
                    if (response.professeur) {
                        const professeur = response.professeur;
                        $.ajax({
                            url: `/sessions/${sessionId}/check-prof`,
                            type: 'POST',
                            data: { prof_id: professeur.id },
                            success: function(checkResponse) {
                                if (checkResponse.isInSession) {
                                    $('#prof-search-results').html('<div class="alert alert-danger">Le professeur est déjà inscrit dans cette session.</div>');
                                } else {
                                    $('#prof-search-results').html(
                                        `<div class="alert alert-success">Professeur trouvé: ${professeur.nomprenom}</div>
                                        <input type="hidden" id="prof-id" value="${professeur.id}">`
                                    );
                                    loadProfSessionDetails(sessionId);
                                    $('#prof-payment-form').show();
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Erreur lors de la vérification du professeur dans la session: ' + error);
                            }
                        });
                    } else {
                        $('#prof-search-results').html('<div class="alert alert-danger">Professeur non trouvé.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Erreur lors de la recherche du professeur: ' + error);
                }
            });
        } else {
            alert('Veuillez entrer un numéro de téléphone.');
        }
    }

    window.loadProfSessionDetails = function(sessionId) {
        $.ajax({
            url: `/sessions/${sessionId}/details`,
            type: 'GET',
            success: function(response) {
                if (response.formation) {
                    // $('#prof-montant').val(response.formation.prix); // Set Montant to Formation Price
                    const today = new Date().toISOString().split('T')[0];
                    $('#prof-date-paiement').val(today); // Set Date de Paiement to today's date
                } else {
                    $('#prof-montant').val(''); // Clear Montant if no formation data is found
                }
            },
            error: function(xhr, status, error) {
                alert('Erreur lors du chargement des détails de la session: ' + error);
            }
        });
    }

    window.updatePaymentFields = function() {
        const typeId = $('#prof-typeymntprofs').val();
        const montantField = $('#prof-montant');
        const montantAPayeField = $('#prof-montant_a_paye');
        const sessionId = $('#new-prof-session_id').val();
        montantField.val(''); // Clear the montant field

        if (typeId == '1') { // Assuming typeymntprofs_id 1 is for percentage
            montantField.attr('placeholder', 'Entrez le pourcentage');
            montantField.attr('max', '100');
            montantField.attr('min', '0');
            montantField.on('input', function() {
                const value = parseInt(this.value, 10);
                if (value < 0 || value > 100) {
                    alert('Le pourcentage doit être compris entre 0 et 100');
                    this.value = '';
                } else {
                    $.ajax({
                        url: `/sessions/${sessionId}/total-student-payments`,
                        type: 'GET',
                        success: function(response) {
                            const totalStudentPayments = response.total;
                            const calculatedMontantAPaye = Math.round((totalStudentPayments * value) / 100);
                            montantAPayeField.val(calculatedMontantAPaye);
                        },
                        error: function(xhr, status, error) {
                            alert('Erreur lors de la récupération des paiements des étudiants: ' + error);
                        }
                    });
                }
            });
        } else if (typeId == '2') { // Assuming typeymntprofs_id 2 is for monthly
            montantField.attr('placeholder', 'Entrez le salaire mensuel');
            montantField.removeAttr('max min');
            montantField.off('input').on('input', function() {
                const monthlySalary = parseInt(this.value, 10);
                if (isNaN(monthlySalary) || monthlySalary <= 0) {
                    montantAPayeField.val('');
                    return;
                }

                getSessionDates(sessionId).done(function(response) {
                    const startDate = new Date(response.start_date);
                    const endDate = new Date(response.end_date);
                    const diffTime = Math.abs(endDate - startDate);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    const months = diffDays / 30;
                    const calculatedMontantAPaye = Math.round(monthlySalary * months);
                    montantAPayeField.val(calculatedMontantAPaye);
                }).fail(function(jqXHR, textStatus) {
                    alert('Erreur lors de la récupération des dates de session: ' + textStatus);
                });
            });
        } else if (typeId == '3') { // Assuming typeymntprofs_id 3 is for hourly
            montantField.attr('placeholder', 'Entrez le tarif horaire');
            montantField.removeAttr('max min');
            montantField.off('input').on('input', function() {
                const hourlyRate = parseInt(this.value, 10);
                if (isNaN(hourlyRate) || hourlyRate <= 0) {
                    montantAPayeField.val('');
                    return;
                }

                const totalHours = 1; // This should be fetched dynamically
                const calculatedMontantAPaye = Math.round(hourlyRate * totalHours);
                montantAPayeField.val(calculatedMontantAPaye);
            });
        }
    }

    // window.openAddProfPaymentModal = function(profId, sessionId) {
    //     $.ajax({
    //         url: `/sessions/${sessionId}/profs/${profId}/details`,
    //         type: 'GET',
    //         success: function(response) {
    //             if (response.success) {
    //                 const resteAPayer = response.reste_a_payer;
    //                 if (resteAPayer <= 0) {
    //                     iziToast.warning({
    //                         message: 'Le professeur a déjà reçu la totalité du paiement.',
    //                         position: 'topRight'
    //                     });
    //                 } else {
    //                     $('#prof-id').val(profId);
    //                     $('#prof-session-id').val(sessionId);
    //                     $('#prof-montant').val(response.montant);
    //                     $('#prof-montant_a_paye').val(response.montant_a_paye);
    //                     $('#prof-reste-a-payer').val(resteAPayer);
    //                     $('#profPaiementAddModal').modal('show');
    //                 }
    //             } else {
    //                 iziToast.error({
    //                     message: response.error,
    //                     position: 'topRight'
    //                 });
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             iziToast.error({
    //                 message: 'Erreur lors de la récupération des détails: ' + error,
    //                 position: 'topRight'
    //             });
    //         }
    //     });
    // }

    window.openAddProfPaymentModal = function(profId, sessionId) {
    $.ajax({
        url: `/sessions/${sessionId}/profs/${profId}/details`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const resteAPayer = response.reste_a_payer;
                if (resteAPayer >= 0) {
                    iziToast.warning({
                        message: 'Le professeur a déjà reçu la totalité du paiement.',
                        position: 'topRight'
                    });
                } else {
                    $('#prof-id').val(profId);
                    $('#prof-session-id').val(sessionId);
                    $('#prof-montant').val(response.montant);
                    $('#prof-montant_a_paye').val(response.montant_a_paye);
                    $('#prof-reste-a-payer').val(resteAPayer);
                    $('#prof-date-paiement').val(response.date_paiement); // Assurez-vous que cette valeur est définie

                    $('#profPaiementAddModal').modal('show');
                }
            } else {
                iziToast.error({
                    message: response.error,
                    position: 'topRight'
                });
            }
        },
        error: function(xhr, status, error) {
            iziToast.error({
                message: 'Erreur lors de la récupération des détails: ' + error,
                position: 'topRight'
            });
        }
    });
}


    window.addProfAndPaiement = function() {
        const profId = $('#prof-id').val();
        const sessionId = $('#new-prof-session_id').val();
        const datePaiement = $('#prof-date-paiement').val();
        const montantAPaye = $('#prof-montant_a_paye').val();
        const montantPaye = $('#prof-montant_paye').val();
        const modePaiement = $('#prof-mode-paiement').val();
        const montant = $('#prof-montant').val();
        const typeId = $('#prof-typeymntprofs').val();

        if (!profId || !sessionId) {
            alert('Professeur ID ou Session ID est manquant.');
            return;
        }

        submitProfAndPaiement(profId, sessionId, datePaiement, montantAPaye, montantPaye, modePaiement, montant, typeId);
    }

    function submitProfAndPaiement(profId, sessionId, datePaiement, montantAPaye, montantPaye, modePaiement, montant, typeId) {
        $.ajax({
            url: `/sessions/${sessionId}/profs/${profId}/add`,
            type: 'POST',
            data: {
                prof_id: profId,
                date_paiement: datePaiement,
                montant_a_paye: montantAPaye,
                montant_paye: montantPaye,
                mode_paiement: modePaiement,
                montant: montant,
                typeymntprofs_id: typeId,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#profAddModal').modal('hide');
                    showProfContents(sessionId); // Refresh the list after adding
                } else {
                    alert(response.error);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                alert('Erreur lors de l\'ajout du professeur: ' + xhr.responseText);
            }
        });
    }

    window.deleteProfFromSession = function(profId, sessionId) {
        if (confirm("Êtes-vous sûr de vouloir supprimer ce professeur de la session ?")) {
            $.ajax({
                url: `/sessions/${sessionId}/profs/${profId}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        iziToast.success({
                            message: response.success,
                            position: 'topRight'
                        });
                        showProfContents(sessionId); // Refresh the list after deleting
                    } else {
                        iziToast.error({
                            message: response.error,
                            position: 'topRight'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        message: 'Erreur lors de la suppression: ' + error,
                        position: 'topRight'
                    });
                }
            });
        }
    }

    window.showProfContents = function(sessionId) {
        $.ajax({
            url: `/sessions/${sessionId}/profcontents`,
            type: 'GET',
            success: function(response) {
                if (response.error) {
                    iziToast.error({ message: response.error, position: 'topRight' });
                    return;
                }

                let html = `<div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card my-4">
                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#profAddModal" onclick="setProfSessionId(${sessionId})" data-toggle="tooltip" title="Ajouter un professeur"><i class="material-icons opacity-10">add</i></button>
                                        <button class="btn btn-secondary" onclick="hideProfContents()">Fermer</button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-2">
                                    <div class="table-responsive p-0" id="sessions-table">
                                        <table class="table align-items-center mb=0">
                                            <thead>
                                                <tr>
                                                    <th>Nom & Prénom</th>
                                                    <th>Phone</th>
                                                    <th>WhatsApp</th>
                                                    <th>Montant</th>
                                                    <th>Montant à Payer</th>
                                                    <th>Montant Payé</th>
                                                    <th>Reste à Payer</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

                if (response.professeurs && response.professeurs.length > 0) {
                    response.professeurs.forEach(function(content) {
                        let montant = content.montant || 0;
                        let montant_a_paye = content.montant_a_paye || 0;
                        let montant_paye = content.montant_paye || 0;
                        let resteAPayer = montant_a_paye - montant_paye;

                        html += `<tr>
                            <td>${content.nomprenom || 'N/A'}</td>
                            <td>${content.phone || 'N/A'}</td>
                            <td>${content.wtsp || 'N/A'}</td>
                            <td>${montant}</td>
                            <td>${montant_a_paye}</td>
                            <td>${montant_paye}</td>
                            <td>${resteAPayer}</td>
                            <td>
                                <button class="btn btn-dark" onclick="openAddProfPaymentModal(${content.id}, ${sessionId})"><i class="material-icons opacity-10">payment</i></button>
                                <button class="btn btn-danger" onclick="deleteProfFromSession(${content.id}, ${sessionId})"><i class="material-icons opacity-10">delete_forever</i></button>
                            </td>
                        </tr>`;
                    });
                } else {
                    html += '<tr><td colspan="8" class="text-center">Aucun professeur trouvé pour cette session.</td></tr>';
                }

                html += `</tbody></table></div></div></div></div></div>`;
                $('#formationProfContents').html(html);
                $('#formationProfContentContainer').show();
                $('html, body').animate({ scrollTop: $('#formationProfContentContainer').offset().top }, 'slow');
            },
            error: function(xhr, status, error) {
                iziToast.error({ message: 'Erreur lors du chargement des contenus: ' + error, position: 'topRight' });
            }
        });
    }

    window.hideProfContents = function() {
        $('#formationProfContentContainer').hide();
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    }
});




</script>
</body>
</html>
