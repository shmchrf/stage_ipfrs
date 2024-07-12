<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Formation</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom session</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date début</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date fin</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sessions as $session)
        <tr>
            <td>{{ $session->id }}</td>
            <td>{{ $session->formation->nom }}</td>
            <td>{{ $session->nom }}</td>
            <td>{{ $session->date_debut }}</td>
            <td>{{ $session->date_fin }}</td>
            <td>
                <button class="btn btn-info" id="edit-session" data-id="{{ $session->id }}"><i class="material-icons opacity-10">border_color</i></button>
                <button class="btn btn-danger" id="delete-session" data-id="{{ $session->id }}"><i class="material-icons opacity-10">delete</i></button>
                <button class="btn btn-secondary" onclick="showContents({{ $session->id }})" data-toggle="tooltip" title="Liste des étudiants">
                    <i class="material-icons opacity-10">group</i>
                </button>
                
                <button class="btn btn-secondary" onclick="showProfContents({{ $session->id }})" data-toggle="tooltip" title="Liste des professeurs">
                    <i class="material-icons opacity-10">assignment_ind</i>
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $sessions->links() }}
