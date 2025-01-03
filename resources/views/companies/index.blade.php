@extends('layouts.app')

@section('content')
<div class="container my-3 mb-5 pb-5">
    <h1 class="mb-4">Empresas</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-between mb-4">
        <!-- Botón para crear un nueva empresa -->
        <a href="{{ route('companies.create') }}" class="btn btn-primary">Nueva Empresa</a>
        <!-- Formulario de filtro -->
        <form action="{{ route('companies.index') }}" method="GET" class="d-flex w-50">
            <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary ms-2">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Tabla de empresas -->
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th><a href="{{ route('companies.index', ['search' => request('search'), 'sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">Name</a></th>
                <th><a href="{{ route('companies.index', ['search' => request('search'), 'sort' => 'city', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">City</a></th>
                <th><a href="{{ route('companies.index', ['search' => request('search'), 'sort' => 'country', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">Country</a></th>
                <th><a href="{{ route('companies.index', ['search' => request('search'), 'sort' => 'email', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">Email</a></th>
                <th><a href="{{ route('companies.index', ['search' => request('search'), 'sort' => 'cif', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">CIF</a></th>
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            @forelse ($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->city }}</td>
                <td>{{ $company->country }}</td>
                <td>{{ $company->email }}</td>
                <td>{{ $company->cif }}</td>
                <td>
                    <div class="d-flex flex-row gap-2">
                        <!-- Botón para ver (Icono) -->
                        <a href="{{ route('companies.show', $company) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>

                        <!-- Botón para editar (Icono) -->
                        <a href="{{ route('companies.edit', $company) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- Botón para abrir la ventana modal de confirmación -->
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal-{{ $company->id }}" >
                            <i class="fas fa-trash"></i>
                        </button>

                        <!-- Modal de confirmación -->
                        <div class="modal fade" id="confirmDeleteModal-{{ $company->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que quieres eliminar la empresa con NIF <strong>{{ $company->cif }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <form id="deleteForm-{{ $company->id }}" action="{{ route('companies.delete', $company->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No se encontraron empresas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Enlaces de paginación -->
    <div class="d-flex justify-content-center">
        {{ $companies->appends(['sort' => $sort, 'direction' => $direction])->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
