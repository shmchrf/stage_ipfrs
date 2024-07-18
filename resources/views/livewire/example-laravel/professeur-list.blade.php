<table class="table align-items-center mb-0">
    <thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom & Prénom</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Types de contrats</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nationalité</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Diplôme</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Genre</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lieu de naissance</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Adresse</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date de naissance</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Portable</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">WhatsApp</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
            <th class="text-secondary opacity-7"></th>
        </tr>
    </thead>
    <tbody>
    @foreach($profs as $prof)
    <tr>
        <td>{{ $prof->id }}</td>
        <td><img src="{{ asset('images/'.$prof->image)}}" alt="" width="60px"></td>
        <td>{{ $prof->nomprenom }}</td>
        <td data-type-id="{{ $prof->type_id }}">{{ $prof->type->type ?? 'N/A' }}</td>
        <td data-country-id="{{ $prof->country_id }}">{{ $prof->country->name ?? 'N/A' }}</td>
        <td>{{ $prof->diplome }}</td>
        <td>{{ $prof->genre }}</td>
        <td>{{ $prof->lieunaissance }}</td>
        <td>{{ $prof->adress }}</td>
        <td>{{ $prof->datenaissance }}</td>
        <td>{{ $prof->email }}</td>
        <td>{{ $prof->phone }}</td>
        <td>{{ $prof->wtsp }}</td>
        <td>
            <a href="javascript:void(0)" id="edit-prof" data-id="{{ $prof->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
            <a href="javascript:void(0)" id="delete-prof" data-id="{{ $prof->id }}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
            <!-- <a href="javascript:void(0)" class="detail-prof" data-id="{{ $prof->id }}" class="btn btn-secondary"><i class="material-icons opacity-10">info</i></a> -->
        </td>
    </tr>
    @endforeach
</tbody>
