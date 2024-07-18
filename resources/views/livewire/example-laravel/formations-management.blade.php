<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Formations Management</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
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
        #formationContentContainer {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div id="formationContentContainer" style="display:none;">
            <!-- <button onclick="$('#formationContentContainer').hide();" class="btn btn-secondary">Fermer</button> -->
            <div id="formationContents"></div>
        </div>
        <div class="row">
            <div class="col-12">
                @if (session('status'))
                <div class="alert alert-success fade-out">
                    {{ session('status')}}
                </div>
                @endif
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#formationAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter 
                            </button>
                            <a href="{{ route('formations.export') }}" class="btn btn-success">Exporter </a>
                        </div>
                        <form action="/search1" method="get" class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search1" id="search_bar" class="form-control" placeholder="Rechercher..." value="{{ isset($search1) ? $search1 : ''}}">
                            </div>
                            <div id="search_list"></div>
                        </form>
                    </div>
                    <!-- <div class="me-3 my-3 text-end"></div> -->
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0" id="formations-table">
                            @include('livewire.example-laravel.formations-list', ['formations' => $formations])
                        </div>
                    </div>
                </div>

    <!-- Modal Ajouter Formation -->
    <div class="modal fade" id="formationAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter une nouvelle Programme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formation-add-form">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="code" class="form-label required">Code:</label>
                            <input type="text" class="form-control" id="new-formation-code" placeholder="Code du programme" name="code">
                            <div class="text-danger" id="code-warning"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label required">Nom :</label>
                            <input type="text" class="form-control" id="new-formation-nom" placeholder="Nom du programme" name="nom">
                            <div class="text-danger" id="nom-warning"></div>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="duree" class="form-label required">Durée:</label>
                            <input type="number" class="form-control" id="new-formation-duree" placeholder="Durée" name="duree">
                            <div class="text-danger" id="duree-warning"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="prix" class="form-label required">Prix:</label>
                            <input type="number" class="form-control" id="new-formation-prix" placeholder="Prix" name="prix">
                            <div class="text-danger" id="prix-warning"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="add-new-formation">Ajouter</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>






    <!-- Modal Modifier Formation -->
    <div class="modal fade" id="formationEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier Programme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formation-edit-form">
                        @csrf
                        <input type="hidden" id="formation-id" name="id">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="code" class="form-label required">Code:</label>
                                <input type="text" class="form-control" id="formation-code" placeholder="Code du programme" name="code" required>
                            <div class="text-danger" id="edit-code-warning"></div>

                            </div>
                            <div class="col-md-6">
                                <label for="nom" class="form-label required">Nom:</label>
                                <input type="text" class="form-control" id="formation-nom" placeholder="Nom du programme" name="nom" required>
                            <div class="text-danger" id="edit-nom-warning"></div>

                            </div>
                        </div>
                        <br>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="duree" class="form-label required">Durée:</label>
                                <input type="number" class="form-control" id="formation-duree" placeholder="Durée" name="duree" required>
                            <div class="text-danger" id="edit-duree-warning"></div>

                            </div>
                            <div class="col-md-6">
                                <label for="prix" class="form-label required">Prix:</label>
                                <input type="number" class="form-control" id="formation-prix" placeholder="Prix" name="prix" required>
                            <div class="text-danger" id="edit-prix-warning"></div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="formation-update">Modifier</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Ajouter Contenu -->
    <div class="modal fade" id="contenuAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouveau Contenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contenu-add-form">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="nomchap" class="form-label required">Nom du Chapitre:</label>
                            <input type="text" class="form-control" id="new-contenu-nomchap" placeholder="Nom du Chapitre" name="nomchap">
                            <div class="text-danger" id="nomchap-warning"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="nomunite" class="form-label required">Nom de l'Unité:</label>
                            <input type="text" class="form-control" id="new-contenu-nomunite" placeholder="Nom de l'Unité" name="nomunite">
                            <div class="text-danger" id="nomunite-warning"></div>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="nombreheures" class="form-label required">Nombre Heures:</label>
                            <input type="number" class="form-control" id="new-contenu-nombreheures" placeholder="Nombre Heures" name="nombreheures">
                            <div class="text-danger" id="nombreheures-warning"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="formation_id" class="form-label required">Formation:</label>
                            <select class="form-control" id="new-contenu-formation_id" name="formation_id">
                                <option value="">Sélectionner Formation</option>
                                @foreach ($formations as $formation)
                                    <option value="{{ $formation->id }}">{{ $formation->nom }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="formation_id-warning"></div>
                        </div>
                    </div>
                    <br>
                    <div class="mb-2">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" id="new-contenu-description" placeholder="Description" name="description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="add-new-contenu">Ajouter</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>



    <!-- Modal Modifier Contenu -->
    <!-- Modal Modifier Contenu -->
    <div class="modal fade" id="contenuEditModal" tabindex="-1" aria-labelledby="contenuEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contenuEditModalLabel">Modifier Contenu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contenu-edit-form">
                    @csrf
                    <input type="hidden" id="contenu-id" name="id">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="nomchap-edit" class="form-label required">Nom du Chapitre:</label>
                            <input type="text" class="form-control" id="nomchap-edit" name="nomchap">
                            <div class="text-danger" id="nomchap-warning-edit"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="nomunite-edit" class="form-label required">Nom de l'Unité:</label>
                            <input type="text" class="form-control" id="nomunite-edit" name="nomunite">
                            <div class="text-danger" id="nomunite-warning-edit"></div>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="nombreheures-edit" class="form-label required">Nombre Heures:</label>
                            <input type="number" class="form-control" id="nombreheures-edit" name="nombreheures">
                            <div class="text-danger" id="nombreheures-warning-edit"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="formation_id-edit" class="form-label required">Formation:</label>
                            <select class="form-control" id="formation_id-edit" name="formation_id">
                                <option value="">Sélectionner Formation</option>
                                @foreach ($formations as $formation)
                                    <option value="{{ $formation->id }}">{{ $formation->nom }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="formation_id-warning-edit"></div>
                        </div>
                    </div>
                    <br>
                    <div class="mb-2">
                        <label for="description-edit" class="form-label">Description:</label>
                        <textarea class="form-control" id="description-edit" name="description"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="update-contenu">Modifier</button>
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
        $('#search_bar').on('keyup', function(){
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('search1') }}",
                    type: "GET",
                    data: {'search1': query},
                    success: function(data){
                        $('#formations-table').html(data.html);
                    }
                });
            });

        window.hideContents = function() {
                $('#formationContentContainer').hide();
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            };

        $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });

        // Ajouter une formation
        $("#add-new-formation").click(function(e) {
    e.preventDefault();

    // Validation des champs requis
    let isValid = true;

    if ($('#new-formation-code').val().trim() === '') {
        isValid = false;
        $('#new-formation-code').addClass('is-invalid');
        $('#code-warning').text('Ce champ est requis.');
    } else {
        $('#new-formation-code').removeClass('is-invalid');
        $('#code-warning').text('');
    }

    if ($('#new-formation-nom').val().trim() === '') {
        isValid = false;
        $('#new-formation-nom').addClass('is-invalid');
        $('#nom-warning').text('Ce champ est requis.');
    } else {
        $('#new-formation-nom').removeClass('is-invalid');
        $('#nom-warning').text('');
    }

    if ($('#new-formation-duree').val().trim() === '') {
        isValid = false;
        $('#new-formation-duree').addClass('is-invalid');
        $('#duree-warning').text('Ce champ est requis.');
    } else {
        $('#new-formation-duree').removeClass('is-invalid');
        $('#duree-warning').text('');
    }

    if ($('#new-formation-prix').val().trim() === '') {
        isValid = false;
        $('#new-formation-prix').addClass('is-invalid');
        $('#prix-warning').text('Ce champ est requis.');
    } else {
        $('#new-formation-prix').removeClass('is-invalid');
        $('#prix-warning').text('');
    }

    if (!isValid) {
        return;
    }

    let form = $('#formation-add-form')[0];
    let data = new FormData(form);

    $.ajax({
        url: "{{ route('formation.store') }}",
        type: "POST",
        data: data,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.error) {
                if (response.error === 'Le code de formation existe déjà.') {
                    $('#new-formation-code').addClass('is-invalid');
                    $('#code-warning').text(response.error);
                } else {
                    iziToast.error({
                        title: 'Erreur',
                        message: response.error,
                        position: 'topRight'
                    });
                }
            } else {
                iziToast.success({
                    title: 'Succès',
                    message: response.success,
                    position: 'topRight'
                });
                $('#formationAddModal').modal('hide');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        },
        error: function(xhr, status, error) {
            if (xhr.status === 409) { // Conflit
                $('#new-formation-code').addClass('is-invalid');
                $('#code-warning').text(xhr.responseJSON.error);
            } else {
                let errorMsg = 'Une erreur est survenue : ' + error;
                iziToast.error({
                    title: 'Erreur',
                    message: errorMsg,
                    position: 'topRight'
                });
            }
        }
    });
});




        // Modifier une formation
        $('body').on('click', '#edit-formation', function () {
            var id = $(this).data('id');
            $.get('/formations/' + id, function (data) {
                $('#formation-id').val(data.formation.id);
                $('#formation-code').val(data.formation.code);
                $('#formation-nom').val(data.formation.nom);
                $('#formation-duree').val(data.formation.duree);
                $('#formation-prix').val(data.formation.prix);
                $('#formationEditModal').modal('show');
            });
        });

        $('#formation-update').click(function (e) {
            e.preventDefault();
            let id = $('#formation-id').val();
            let form = $('#formation-edit-form')[0];
            let data = new FormData(form);
            data.append('_method', 'PUT');

            $.ajax({
                url: '/formations/' + id,
                type: 'POST',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status == 404) {
                        iziToast.error({
                            title: 'Erreur',
                            message: response.message,
                            position: 'topRight'
                        });
                    } else {
                        iziToast.success({
                            title: 'Succès',
                            message: response.message,
                            position: 'topRight'
                        });
                        $('#formationEditModal').modal('hide');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        });

        // Supprimer une formation
        $('body').on('click', '#delete-formation', function (e) {
            e.preventDefault();
            var id = $(this).data('id');

            $.ajax({
                url: '/formations/' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 400 && response.has_contents) {
                        if (confirm(response.message)) {
                            // Si l'utilisateur confirme, envoyer une requête pour supprimer la formation et ses contenus
                            $.ajax({
                                url: '/formations/confirm-delete/' + id,
                                type: 'DELETE',
                                dataType: 'json',
                                success: function (response) {
                                    if (response.status === 200) {
                                        iziToast.success({
                                            message: response.message,
                                            position: 'topRight'
                                        });
                                        location.reload();
                                    } else {
                                        iziToast.error({
                                            message: response.message,
                                            position: 'topRight'
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                                    iziToast.error({
                                        title: 'Erreur',
                                        message: 'Une erreur s\'est produite: ' + errorMessage,
                                        position: 'topRight'
                                    });
                                }
                            });
                        }
                    } else if (response.status === 200) {
                        iziToast.success({
                            message: response.message,
                            position: 'topRight'
                        });
                        location.reload();
                    } else {
                        iziToast.error({
                            message: response.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    iziToast.error({
                        title: 'Erreur',
                        message: 'Une erreur s\'est produite: ' + errorMessage,
                        position: 'topRight'
                    });
                }
            });
        });

        // Ajouter un contenu
        $("#add-new-contenu").click(function(e) {
    e.preventDefault();

    // Validation des champs requis
    let isValid = true;

    if ($('#new-contenu-nomchap').val().trim() === '') {
        isValid = false;
        $('#new-contenu-nomchap').addClass('is-invalid');
        $('#nomchap-warning').text('Ce champ est requis.');
    } else {
        $('#new-contenu-nomchap').removeClass('is-invalid');
        $('#nomchap-warning').text('');
    }

    if ($('#new-contenu-nomunite').val().trim() === '') {
        isValid = false;
        $('#new-contenu-nomunite').addClass('is-invalid');
        $('#nomunite-warning').text('Ce champ est requis.');
    } else {
        $('#new-contenu-nomunite').removeClass('is-invalid');
        $('#nomunite-warning').text('');
    }

    if ($('#new-contenu-nombreheures').val().trim() === '') {
        isValid = false;
        $('#new-contenu-nombreheures').addClass('is-invalid');
        $('#nombreheures-warning').text('Ce champ est requis.');
    } else {
        $('#new-contenu-nombreheures').removeClass('is-invalid');
        $('#nombreheures-warning').text('');
    }

    if ($('#new-contenu-formation_id').val().trim() === '') {
        isValid = false;
        $('#new-contenu-formation_id').addClass('is-invalid');
        $('#formation_id-warning').text('Ce champ est requis.');
    } else {
        $('#new-contenu-formation_id').removeClass('is-invalid');
        $('#formation_id-warning').text('');
    }

    if (!isValid) {
        return;
    }

    let form = $('#contenu-add-form')[0];
    let data = new FormData(form);

    $.ajax({
        url: "{{ route('contenus.store') }}",
        type: "POST",
        data: data,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.error) {
                iziToast.error({
                    title: 'Erreur',
                    message: response.error,
                    position: 'topRight'
                });
            } else {
                iziToast.success({
                    title: 'Succès',
                    message: response.success,
                    position: 'topRight'
                });
                $('#contenuAddModal').modal('hide');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        },
        error: function(xhr, status, error) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                $.each(xhr.responseJSON.errors, function(field, errors) {
                    let fieldId = '#new-contenu-' + field;
                    $(fieldId).addClass('is-invalid');
                    $(fieldId + '-warning').text(errors.join(', '));
                });
            } else {
                let errorMsg = 'Une erreur est survenue : ' + error;
                iziToast.error({
                    title: 'Erreur',
                    message: errorMsg,
                    position: 'topRight'
                });
            }
        }
    });
});

        // Modifier un contenu
        // window.editContent = function(contentId) {
        //     $.get('/contenus/' + contentId, function (data) {
        //         $('#contenu-id').val(data.contenu.id);
        //         $('#nomchap-edit').val(data.contenu.nomchap);
        //         $('#nomunite-edit').val(data.contenu.nomunite);
        //         $('#description-edit').val(data.contenu.description);
        //         $('#nombreheures-edit').val(data.contenu.nombreheures);
        //         $('#formation_id-edit').val(data.contenu.formation_id);
        //         $('#contenuEditModal').modal('show');
        //     });
        // }

        // $('#update-contenu').click(function (e) {
        //     e.preventDefault();
        //     let id = $('#contenu-id').val();
        //     let form = $('#contenu-edit-form')[0];
        //     let data = new FormData(form);
        //     data.append('_method', 'PUT');

        //     $.ajax({
        //         url: '/contenus/' + id,
        //         type: 'POST',
        //         data: data,
        //         dataType: 'json',
        //         processData: false,
        //         contentType: false,
        //         success: function (response) {
        //             if (response.error) {
        //                 iziToast.error({
        //                     title: 'Erreur',
        //                     message: response.error,
        //                     position: 'topRight'
        //                 });
        //             } else {
        //                 iziToast.success({
        //                     title: 'Succès',
        //                     message: response.success,
        //                     position: 'topRight'
        //                 });
        //                 $('#contenuEditModal').modal('hide');
        //                 setTimeout(function () {
        //                     location.reload();
        //                 }, 1000);
        //             }
        //         }
        //     });
        // });

        // Supprimer un contenu
        window.deleteContent = function(contentId) {
            if (confirm("Voulez-vous vraiment supprimer ce contenu?")) {
                $.ajax({
                    url: '/contenus/' + contentId,
                    type: 'DELETE',
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            iziToast.success({
                                title: 'Succès',
                                message: response.success,
                                position: 'topRight'
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        } else {
                            iziToast.error({
                                title: 'Erreur',
                                message: response.error,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        iziToast.error({
                            title: 'Erreur',
                            message: 'Une erreur s\'est produite: ' + error,
                            position: 'topRight'
                        });
                    }
                });
            }
        }


        $('body').on('click', '#delete-formation', function (e) {
        e.preventDefault();
        var id = $(this).data('id');

        $.ajax({
            url: '/formations/' + id + '/delete',
            type: 'DELETE',
            dataType: 'json',
            success: function (response) {
                if (response.status === 400) {
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                } else if (response.status === 200 && response.confirm_deletion) {
                    if (confirm(response.message)) {
                        // Si l'utilisateur confirme, envoyer une requête pour supprimer la formation
                        $.ajax({
                            url: '/formations/' + id + '/confirm-delete',
                            type: 'DELETE',
                            dataType: 'json',
                            success: function (response) {
                                if (response.status === 200) {
                                    iziToast.success({
                                        message: response.message,
                                        position: 'topRight'
                                    });
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    iziToast.error({
                                        message: response.message,
                                        position: 'topRight'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                var errorMessage = xhr.status + ': ' + xhr.statusText;
                                iziToast.error({
                                    title: 'Erreur',
                                    message: 'Une erreur s\'est produite: ' + errorMessage,
                                    position: 'topRight'
                                });
                            }
                        });
                    }
                } else {
                    iziToast.error({
                        message: response.message,
                        position: 'topRight'
                    });
                }
            },
            error: function (xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                iziToast.error({
                    title: 'Erreur',
                    message: 'Une erreur s\'est produite: ' + errorMessage,
                    position: 'topRight'
                });
            }
        });
    });



        // Afficher les contenus de la formation
        // Afficher les contenus de la formation
        $(document).ready(function () {
    // Autres configurations et fonctions...

    // Modifier un contenu
    // Modifier un contenu
    $(document).ready(function () {
    // Modifier un contenu
    window.editContent = function(contentId) {
        $.get('/contenus/' + contentId, function (data) {
            $('#contenu-id').val(data.contenu.id);
            $('#nomchap-edit').val(data.contenu.nomchap);
            $('#nomunite-edit').val(data.contenu.nomunite);
            $('#description-edit').val(data.contenu.description);
            $('#nombreheures-edit').val(data.contenu.nombreheures);
            $('#formation_id-edit').val(data.contenu.formation_id);
            $('#contenuEditModal').modal('show');
        });
    };

    $('#update-contenu').click(function (e) {
        e.preventDefault();
        let id = $('#contenu-id').val();
        let form = $('#contenu-edit-form')[0];
        let data = new FormData(form);
        data.append('_method', 'PUT');

        $.ajax({
            url: '/contenus/' + id,
            type: 'POST',
            data: data,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.error) {
                    iziToast.error({
                        title: 'Erreur',
                        message: response.error,
                        position: 'topRight'
                    });
                } else {
                    iziToast.success({
                        title: 'Succès',
                        message: response.success,
                        position: 'topRight'
                    });
                    $('#contenuEditModal').modal('hide');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            }
        });
    });
});

    // Supprimer un contenu
    window.deleteContent = function(contentId) {
        if (confirm("Voulez-vous vraiment supprimer ce contenu?")) {
            $.ajax({
                url: '/contenus/' + contentId,
                type: 'DELETE',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        iziToast.success({
                            title: 'Succès',
                            message: response.success,
                            position: 'topRight'
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        iziToast.error({
                            title: 'Erreur',
                            message: response.error,
                            position: 'topRight'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    iziToast.error({
                        title: 'Erreur',
                        message: 'Une erreur s\'est produite: ' + error,
                        position: 'topRight'
                    });
                }
            });
        }
    }

    // Afficher les contenus de la formation
    window.showContents = function(formationId) {
        $.ajax({
            url: '/formations/' + formationId + '/contents',
            type: 'GET',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }

                let html = `
                <div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="card my-4">
                                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#contenuAddModal" onclick="setFormationId(${formationId})" data-toggle="tooltip" title="ajouter un contenu"><i class="material-icons opacity-10">add</i></button>
                                        <button class="btn btn-secondary" onclick="hideContents()">Fermer</button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-2">
                                    <div class="table-responsive p-0" id="sessions-table">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Chapitre</th>
                                                    <th>Unité</th>
                                                    <th>Description</th>
                                                    <th>Nombre Heures</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;

                if (response.contenus.length > 0) {
                    response.contenus.forEach(function(content) {
                        html += `<tr>
                            <td>${content.id}</td>
                            <td>${content.nomchap}</td>
                            <td>${content.nomunite}</td>
                            <td>${content.description}</td>
                            <td>${content.nombreheures}</td>
                             <td>
                                 <a href="javascript:void(0)" class="btn btn-info" onclick="editContent(${content.id})"><i class="material-icons opacity-10">border_color</i></a>
                                <a href="javascript:void(0)" class="btn btn-danger" onclick="deleteContent(${content.id})"><i class="material-icons opacity-10">delete</i></a>
                             </td>
                            <td>


                        </tr>`;
                    });
                } else {
                    html += '<tr><td colspan="6" class="text-center">Aucun Contenus trouvé pour cet Programme.</td></tr>';
                }

                html += `</tbody>
                        </table>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>`;

                $('#formationContents').html(html);
                $('#formationContentContainer').show();
                $('html, body').animate({ scrollTop: $('#formationContentContainer').offset().top }, 'slow');
            },
            error: function() {
                alert('Erreur lors du chargement des contenus.');
            }
        });
    }

    window.setFormationId = function(formationId) {
        $('#formation-id-contenu').val(formationId);
    };
});



        window.setFormationId = function(formationId) {
            $('#formation-id-contenu').val(formationId);
        };
    });
    </script>
</body>
</html>