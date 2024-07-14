

<table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Formation</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom du Chapitre</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom de l'unité</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nombre des Heures</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Description</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($contenues as $$content)
                                    <tr>
                                        <td>{{ $$content->id }}</td>
                                        <td><a href="javascript:void(0)" id="show-formation" data-id="{{ $$content->id }}" >{{ $$content->formation->nom ?? 'N/A' }}</a></td>
                                        <td>{{ $$content->nomchap}}</td>
                                        <td>{{ $$content->nomunite}}</td>
                                        <td>{{ $$content->nombreheures }}</td>
                                        <td>{{ $$content->description }}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" id="edit-contenue" data-id="{{ $$content->id }}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                                            <a href="javascript:void(0)" id="delete-contenue" data-id="{{ $$content->id }}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $contenues->links() }}


