<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Etudiants Management</title>
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
            border-radius: 6px;
        }
        .form-control:focus {
            border-color: #66afe9;
            outline: 0;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
            border-radius: solid 2px;
        }
        .modal-content {
            max-width: 800px;
            margin: 0 auto;
        }
        .modal-body .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container-fluid py-4">
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
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#etudiantAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter 
                            </button>
                            <a href="{{ route('export.etudiants') }}" class="btn btn-success">Exporter </a>
                        </div>
                        <form class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" id="search_bar" class="form-control" placeholder="Rechercher...">
                            </div>
                        </form>
                    </div>
                    <div class="me-3 my-3 text-end "></div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0" id="etudiants-table">
                            @include('livewire.example-laravel.etudiants-list', ['etudiants' => $etudiants])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
<!-- Add Student Modal -->
<div class="modal fade" id="etudiantAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 40cm;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouvel étudiant</h5>
            </div>
            <div class="modal-body">
                <form id="etudiant-add-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="image" class="form-label">Image:</label>
                            <input type="file" class="form-control" id="new-etudiant-image" name="image">
                        </div>
                        <div class="col-md-3">
                            <label for="nni" class="form-label required">NNI:</label>
                            <input type="number" class="form-control" id="new-etudiant-nni" placeholder="NNI" name="nni">
                            <div class="text-danger" id="nni-warning"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="nomprenom" class="form-label required">Nom & Prénom:</label>
                            <input type="text" class="form-control" id="new-etudiant-nomprenom" placeholder="Nom & Prénom" name="nomprenom">
                            <div class="text-danger" id="nomprenom-warning"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="lieunaissance" class="form-label">Lieu de naissance:</label>
                            <input type="text" class="form-control" id="new-etudiant-lieunaissance" placeholder="Lieu de naissance" name="lieunaissance">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="form-group col-md-3">
                            <label for="country_id" class="form-label required">Nationalité</label>
                            <select class="form-control" id="new-etudiant-country_id" name="country_id">
                                <option value="">Choisir la nationalité</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="country_id-warning"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="diplome" class="form-label">Diplôme:</label>
                            <input type="text" class="form-control" id="new-etudiant-diplome" placeholder="Diplôme" name="diplome">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Genre:</label>
                            <div>
                                <input type="radio" id="male" name="genre" value="Male">
                                <label for="male">Male</label>
                                <input type="radio" id="female" name="genre" value="Female">
                                <label for="female">Female</label>
                            </div>
                            <div class="text-danger" id="genre-warning"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="datenaissance" class="form-label">Date de naissance:</label>
                            <input type="date" class="form-control" id="new-etudiant-datenaissance" placeholder="Date de naissance" name="datenaissance">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="new-etudiant-email" placeholder="email@example.com" name="email">
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label required">Portable:</label>
                            <input type="number" class="form-control" id="new-etudiant-phone" placeholder="Portable" name="phone">
                            <div class="text-danger" id="phone-warning"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="wtsp" class="form-label">WhatsApp:</label>
                            <input type="number" class="form-control" id="new-etudiant-wtsp" placeholder="WhatsApp" name="wtsp">
                        </div>
                        <div class="col-md-3">
                            <label for="adress" class="form-label">Adresse:</label>
                            <input type="text" class="form-control" id="new-etudiant-adress" placeholder="Adresse" name="adress">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="add-new-etudiant">Ajouter</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>





    <!-- Edit Student Modal -->
    <!-- Edit Student Modal -->
<div class="modal fade" id="etudiantEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 40cm;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier Etudiant</h5>
            </div>
            <div class="modal-body">
                <form id="etudiant-edit-form" enctype="multipart/form-data">
                    <input type="hidden" id="etudiant-id" name="id">
                    <div class="d-flex align-items-center mb-3">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="image" class="form-label">Image:</label>
                                <img src="" id="imagePreview" class="imgUpload" alt="">
                                <div>
                                    <input type="file" class="form-control" id="image" name="image">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="nni" class="form-label required">NNI:</label>
                                <input type="text" class="form-control" id="etudiant-nni" placeholder="NNI" name="nni">
                                <div class="text-danger" id="edit-nni-warning"></div>
                            </div>
                            <div class="col-md-3">
                                <label for="nomprenom" class="form-label required">Nom & Prénom:</label>
                                <input type="text" class="form-control" id="etudiant-nomprenom" placeholder="Nom & Prénom" name="nomprenom">
                                <div class="text-danger" id="edit-nomprenom-warning"></div>
                            </div>
                            <div class="col-md-3">
                                <label for="lieunaissance" class="form-label">Lieu de naissance:</label>
                                <input type="text" class="form-control" id="etudiant-lieunaissance" placeholder="Lieu de naissance" name="lieunaissance">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="form-group col-md-3">
                            <label for="country_id" class="form-label required">Nationalité</label>
                            <select class="form-control" id="etudiant-country_id" name="country_id">
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="edit-country_id-warning"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="diplome" class="form-label">Diplôme:</label>
                            <input type="text" class="form-control" id="etudiant-diplome" placeholder="Diplôme" name="diplome">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label required">Genre:</label>
                            <div>
                                <input type="radio" id="male" name="genre" value="Male">
                                <label for="male">Male</label>
                                <input type="radio" id="female" name="genre" value="Female">
                                <label for="female">Female</label>
                            </div>
                            <div class="text-danger" id="genre-warning"></div>
                        </div>

                        <div class="col-md-3">
                            <label for="datenaissance" class="form-label">Date de naissance:</label>
                            <input type="date" class="form-control" id="etudiant-datenaissance"  name="datenaissance">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="etudiant-email" placeholder="email@example.com" name="email">
                        </div>
                        <div class="col-md-3">
                            <label for="phone" class="form-label required">Portable:</label>
                            <input type="text" class="form-control" id="etudiant-phone" placeholder="Portable" name="phone">
                            <div class="text-danger" id="edit-phone-warning"></div>
                        </div>
                        <div class="col-md-3">
                            <label for="wtsp" class="form-label">WhatsApp:</label>
                            <input type="text" class="form-control" id="etudiant-wtsp" placeholder="WhatsApp" name="wtsp">
                        </div>
                        <div class="col-md-3">
                            <label for="adress" class="form-label">Adresse:</label>
                            <input type="text" class="form-control" id="etudiant-adress" placeholder="Adresse" name="adress">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="etudiant-update">Modifier</button>
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
                    url: "{{ route('search') }}",
                    type: "GET",
                    data: {'search': query},
                    success: function(data){
                        $('#etudiants-table').html(data.html);
                    }
                });
            });

    // Initialize DataTable
    $(document).ready(function() {
        $('#etudiants-table').DataTable({
            "order": [[4, "asc"]] // Ordre alphabétique sur la colonne "Nom & Prénom"
        });
    });

    // Validate form fields
    // function validateForm(formId, warnings) {
    //     let isValid = true;
    //     for (let field in warnings) {
    //         const input = $(formId + ' #' + field);
    //         const warning = $(warnings[field]);
    //         if (input.length === 0) {
    //             console.warn(`No input found with ID: ${field}`);
    //             continue;
    //         }
    //         if (input.attr('type') === 'radio') {
    //             if (!$('input[name="' + field + '"]:checked').val()) {
    //                 warning.text('Ce champ est requis.');
    //                 isValid = false;
    //             } else {
    //                 warning.text('');
    //             }
    //         } else if (input.val().trim() === '') {
    //             warning.text('Ce champ est requis.');
    //             isValid = false;
    //         } else if (field === 'new-etudiant-phone' || field === 'etudiant-phone') {
    //             if (!/^\d{8}$/.test(input.val())) {
    //                 warning.text('Le numéro de téléphone doit comporter 8 chiffres.');
    //                 isValid = false;
    //             } else {
    //                 warning.text('');
    //             }
    //         } else if (field === 'new-etudiant-nni' || field === 'etudiant-nni') {
    //             if (!/^\d{10}$/.test(input.val())) {
    //                 warning.text('Le NNI doit comporter 10 chiffres.');
    //                 isValid = false;
    //             } else {
    //                 warning.text('');
    //             }
    //         } else if (field === 'new-etudiant-email' || field === 'etudiant-email') {
    //             const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    //             if (!emailPattern.test(input.val())) {
    //                 warning.text('Veuillez entrer une adresse e-mail valide.');
    //                 isValid = false;
    //             } else {
    //                 warning.text('');
    //             }
    //         } else {
    //             warning.text('');
    //         }
    //     }
    //     return isValid;
    // }

    // AJAX call for adding a new student
    // $("#add-new-etudiant").click(function(e){
    //     e.preventDefault();
    //     if (!validateForm('#etudiant-add-form', {
    //         'new-etudiant-nni': '#nni-warning',
    //         'new-etudiant-nomprenom': '#nomprenom-warning',
    //         'new-etudiant-country_id': '#country_id-warning',
    //         'new-etudiant-phone': '#phone-warning',
    //         'genre': '#genre-warning'
    //     })) {
    //         return;
    //     }
    //     let form = $('#etudiant-add-form')[0];
    //     let data = new FormData(form);

    //     $.ajax({
    //         url: "{{ route('etudiant.store') }}",
    //         type: "POST",
    //         data: data,
    //         dataType: "JSON",
    //         processData: false,
    //         contentType: false,
    //         success: function(response) {
    //             if (response.errors) {
    //                 var errorMsg = '';
    //                 $.each(response.errors, function(field, errors) {
    //                     $.each(errors, function(index, error) {
    //                         errorMsg += error + '<br>';
    //                     });
    //                 });
    //                 iziToast.error({
    //                     message: errorMsg,
    //                     position: 'topRight'
    //                 });
    //             } else {
    //                 iziToast.success({
    //                     message: response.success,
    //                     position: 'topRight'
    //                 });
    //                 $('#etudiantAddModal').modal('hide');
    //                 setTimeout(function () {
    //                     location.reload();
    //                 }, 1000);
    //                 addStudentToTable(response.etudiant);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             var errorMsg = '';
    //             if (xhr.responseJSON && xhr.responseJSON.errors) {
    //                 $.each(xhr.responseJSON.errors, function(field, errors) {
    //                     $.each(errors, function(index, error) {
    //                         errorMsg += error + '<br>';
    //                     });
    //                 });
    //             } else {
    //                 errorMsg = 'Une erreur est survenue : ' + error;
    //             }
    //             iziToast.error({
    //                 message: errorMsg,
    //                 position: 'topRight'
    //             });
    //         }
    //     });
    // });

    function validateForm(formId, warnings) {
    let isValid = true;
    for (let field in warnings) {
        const input = $(formId + ' #' + field);
        const warning = $(warnings[field]);

        if (input.length === 0) {
            console.warn(`No input found with ID: ${field}`);
            continue;
        }

        if (input.attr('type') === 'radio') {
            if (!$('input[name="' + field + '"]:checked').val()) {
                warning.text('Ce champ est requis.');
                isValid = false;
            } else {
                warning.text('');
            }
        } else if (input.val().trim() === '') {
            warning.text('Ce champ est requis.');
            isValid = false;
        } else if (field === 'new-etudiant-phone' || field === 'etudiant-phone') {
            if (!/^\d{8}$/.test(input.val())) {
                warning.text('Le numéro de téléphone doit comporter 8 chiffres.');
                isValid = false;
            } else {
                warning.text('');
            }
        } else if (field === 'new-etudiant-nni' || field === 'etudiant-nni') {
            if (!/^\d{10}$/.test(input.val())) {
                warning.text('Le NNI doit comporter 10 chiffres.');
                isValid = false;
            } else {
                warning.text('');
            }
        } else if (field === 'new-etudiant-email' || field === 'etudiant-email') {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(input.val())) {
                warning.text('Veuillez entrer une adresse e-mail valide.');
                isValid = false;
            } else {
                warning.text('');
            }
        } else {
            warning.text('');
        }
    }
    return isValid;
}

$("#add-new-etudiant").click(function (e) {
    e.preventDefault();

    if (!validateForm('#etudiant-add-form', {
        'new-etudiant-nni': '#nni-warning',
        'new-etudiant-nomprenom': '#nomprenom-warning',
        'new-etudiant-country_id': '#country_id-warning',
        'new-etudiant-phone': '#phone-warning',
        'genre': '#genre-warning'
    })) {
        return;
    }

    let form = $('#etudiant-add-form')[0];
    let data = new FormData(form);

    $.ajax({
        url: "{{ route('etudiant.store') }}",
        type: "POST",
        data: data,
        dataType: "JSON",
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.errors) {
                var errorMsg = '';
                $.each(response.errors, function (field, errors) {
                    $.each(errors, function (index, error) {
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
                $('#etudiantAddModal').modal('hide');
                setTimeout(function () {
                    location.reload();
                }, 1000);
                addStudentToTable(response.etudiant);
            }
        },
        error: function (xhr, status, error) {
            var errorMsg = '';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMsg = xhr.responseJSON.error;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                $.each(xhr.responseJSON.errors, function (field, errors) {
                    $.each(errors, function (index, error) {
                        errorMsg += error + '<br>';
                    });
                });
            } else {
                errorMsg = 'Une erreur est survenue : ' + error;
            }
            iziToast.error({
                message: errorMsg,
                position: 'topRight'
            });
        }
    });
});
function addStudentToTable(etudiant) {
    let newRow = `<tr>
        <td>${etudiant.nni}</td>
        <td>${etudiant.nomprenom}</td>
        <td>${etudiant.genre}</td>
        <td>${etudiant.phone}</td>
        <td>${etudiant.email}</td>
        <td>${etudiant.country_id}</td>
        <td>
            <!-- Add any other necessary fields and actions here -->
        </td>
    </tr>`;

    $('#students-table tbody').append(newRow);
}


    // Populate the edit modal with the selected student's data
    $('body').on('click', '#edit-etudiant', function () {
        var tr = $(this).closest('tr');
        $('#etudiant-id').val($(this).data('id'));
        $('#etudiant-nni').val(tr.find("td:nth-child(3)").text());
        $('#etudiant-nomprenom').val(tr.find("td:nth-child(4)").text());
        $('#etudiant-country_id').val(tr.find("td:nth-child(5)").data('country-id'));
        $('#etudiant-diplome').val(tr.find("td:nth-child(6)").text());
        var genre = tr.find("td:nth-child(7)").text();
        $('input[name="genre"][value="' + genre + '"]').prop('checked', true);
        $('#etudiant-lieunaissance').val(tr.find("td:nth-child(8)").text());
        $('#etudiant-adress').val(tr.find("td:nth-child(9)").text());
        $('#etudiant-datenaissance').val(tr.find("td:nth-child(10)").text());
        $('#etudiant-email').val(tr.find("td:nth-child(11)").text());
        $('#etudiant-phone').val(tr.find("td:nth-child(12)").text());
        $('#etudiant-wtsp').val(tr.find("td:nth-child(13)").text());
        $('#imagePreview').attr('src', tr.find("td:nth-child(2) img").attr('src'));

        $('#etudiantEditModal').modal('show');
    });

    // AJAX call for updating student details
    $('body').on('click', '#etudiant-update', function () {
        if (!validateForm('#etudiant-edit-form', {
            'etudiant-nni': '#edit-nni-warning',
            'etudiant-nomprenom': '#edit-nomprenom-warning',
            'etudiant-country_id': '#edit-country_id-warning',
            'etudiant-phone': '#edit-phone-warning',
            'genre': '#edit-genre-warning'
        })) {
            return;
        }
        var id = $('#etudiant-id').val();
        var formData = new FormData($('#etudiant-edit-form')[0]);
        formData.append('_method', 'PUT');

        $.ajax({
            url: "{{ route('etudiant.update', '') }}/" + id,
            type: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#etudiantEditModal').modal('hide');
                setTimeout(function () {
                    location.reload();
                }, 1000);
                if (response.success) {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                    updateStudentInTable(response.etudiant);
                } else {
                    iziToast.error({
                        message: response.error,
                        position: 'topRight'
                    });
                }
            },
            error: function(xhr, status, error) {
                var errorMsg = '';
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function(field, errors) {
                        $.each(errors, function(index, error) {
                            errorMsg += error + '<br>';
                        });
                    });
                } else {
                    errorMsg = 'Une erreur est survenue : ' + error;
                }
                iziToast.error({
                    message: errorMsg,
                    position: 'topRight'
                });
            }
        });
    });

    // AJAX call for deleting a student with validation and confirmation
    $('body').on('click', '#delete-etudiant', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        // Step 1: Check if the student can be deleted
        $.ajax({
            url: "{{ route('etudiant.delete', '') }}/" + id,
            type: 'GET',
            success: function(response) {
                if (response.status === 200 && response.confirm_deletion) {
                    // Ask for confirmation before proceeding with deletion
                    var confirmation = confirm(response.message);
                    if (confirmation) {
                        // Step 2: Confirm deletion
                        $.ajax({
                            url: "{{ route('etudiant.confirm_delete', '') }}/" + id,
                            type: 'DELETE',
                            success: function(response) {
                                if (response.status === 200) {
                                    iziToast.success({
                                        message: response.message,
                                        position: 'topRight'
                                    });
                                    removeStudentFromTable(id);
                                } else {
                                    iziToast.error({
                                        message: response.message,
                                        position: 'topRight'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                iziToast.error({
                                    message: 'Une erreur est survenue : ' + error,
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
            error: function(xhr, status, error) {
                iziToast.error({
                    message: 'Une erreur est survenue : ' + error,
                    position: 'topRight'
                });
            }
        });
    });

    // function addStudentToTable(etudiant) {
    //     var newRow = `<tr id="student-${etudiant.id}">
    //         <td>${etudiant.id}</td>
    //         <td><img src="{{ asset('images/') }}/${etudiant.image}" alt="" width="60px"></td>
    //         <td>${etudiant.nni}</td>
    //         <td>${etudiant.nomprenom}</td>
    //         <td data-country-id="${etudiant.country_id}">${etudiant.country ? etudiant.country.name : 'N/A'}</td>
    //         <td>${etudiant.diplome}</td>
    //         <td>${etudiant.genre}</td>
    //         <td>${etudiant.lieunaissance}</td>
    //         <td>${etudiant.adress}</td>
    //         <td>${etudiant.datenaissance}</td>
    //         <td>${etudiant.email}</td>
    //         <td>${etudiant.phone}</td>
    //         <td>${etudiant.wtsp}</td>
    //         <td>
    //             <a href="javascript:void(0)" id="edit-etudiant" data-id="${etudiant.id}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
    //             <a href="javascript:void(0)" id="delete-etudiant" data-id="${etudiant.id}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
    //         </td>
    //     </tr>`;
    //     $('table tbody').append(newRow);
    // }
    $(document).ready(function() {
    $('body').on('click', '.detail-etudiant', function() { // Change to class selector
        var etudiantId = $(this).data('id');
        fetchEtudiantDetails(etudiantId);
    });
});

function fetchEtudiantDetails(etudiantId) {
    $.ajax({
        url: `/etudiants/${etudiantId}/details`,
        type: 'GET',
        success: function(response) {
            if (response.error) {
                iziToast.error({ message: response.error, position: 'topRight' });
                return;
            }

            let formationsHtml = '';
            if (response.formations.length > 0) {
                formationsHtml = response.formations.map(formation => `
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Formation:</strong> ${formation.nom}</div>
                        <div class="col-md-4"><strong>Montant Payé:</strong> ${formation.montant_paye}</div>
                        <div class="col-md-4"><strong>Reste à Payer:</strong> ${formation.reste_a_payer}</div>
                    </div>
                `).join('');
            } else {
                formationsHtml = '<div class="row"><div class="col-md-12">Aucune formation inscrite.</div></div>';
            }

            var detailsHtml = `
                <div class="modal fade" id="etudiantDetailsModal" tabindex="-1" aria-labelledby="etudiantDetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="etudiantDetailsModalLabel">Détails de l'étudiant</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-2">
                                    <div class="col-md-6"><strong>Nom & Prénom:</strong> ${response.etudiant.nomprenom}</div>
                                    <div class="col-md-6"><strong>Numéro de Téléphone:</strong> ${response.etudiant.phone}</div>
                                </div>
                                <hr>
                                ${formationsHtml}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove any existing modals before appending a new one
            $('#etudiantDetailsModal').remove();
            $('body').append(detailsHtml);
            $('#etudiantDetailsModal').modal('show');
        },
        error: function(xhr, status, error) {
            iziToast.error({ message: 'Erreur lors de la récupération des détails: ' + error, position: 'topRight' });
        }
    });
}



    function addStudentToTable(etudiant) {
        var newRow = `<tr id="student-${etudiant.id}">
            <td>${etudiant.id}</td>
            <td><img src="{{ asset('images/') }}/${etudiant.image}" alt="" width="60px"></td>
            <td>${etudiant.nni}</td>
            <td>${etudiant.nomprenom}</td>
            <td data-country-id="${etudiant.country_id}">${etudiant.country ? etudiant.country.name : 'N/A'}</td>
            <td>${etudiant.diplome}</td>
            <td>${etudiant.genre}</td>
            <td>${etudiant.lieunaissance}</td>
            <td>${etudiant.adress}</td>
            <td>${etudiant.datenaissance}</td>
            <td>${etudiant.email}</td>
            <td>${etudiant.phone}</td>
            <td>${etudiant.wtsp}</td>
            <td>
                <a href="javascript:void(0)" id="edit-etudiant" data-id="${etudiant.id}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                <a href="javascript:void(0)" id="delete-etudiant" data-id="${etudiant.id}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
            </td>
        </tr>`;
        $('table tbody').append(newRow);
    }

    function updateStudentInTable(etudiant) {
        var row = $('#student-' + etudiant.id);
        row.find('td:nth-child(2) img').attr('src', '{{ asset("images") }}/' + etudiant.image);
        row.find('td:nth-child(3)').text(etudiant.nni);
        row.find('td:nth-child(4)').text(etudiant.nomprenom);
        row.find('td:nth-child(5)').text(etudiant.country ? etudiant.country.name : 'N/A').attr('data-country-id', etudiant.country_id);
        row.find('td:nth-child(6)').text(etudiant.diplome);
        row.find('td:nth-child(7)').text(etudiant.genre);
        row.find('td:nth-child(8)').text(etudiant.lieunaissance);
        row.find('td:nth-child(9)').text(etudiant.adress);
        row.find('td:nth-child(10)').text(etudiant.datenaissance);
        row.find('td:nth-child(11)').text(etudiant.email);
        row.find('td:nth-child(12)').text(etudiant.phone);
        row.find('td:nth-child(13)').text(etudiant.wtsp);
    }

    function removeStudentFromTable(id) {
        $('#student-' + id).remove();
    }

    var alertElement = document.querySelector('.fade-out');
    if (alertElement) {
        setTimeout(function() {
            alertElement.style.display = 'none';
        }, 2000);
    }
});
</script>





</body>
</html>