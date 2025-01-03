@extends('layouts.app')

@section('content')
<div class="container my-3 mb-5 pb-5">
    <h1 class="mb-4">Contratos</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-between mb-4">
        <!-- Botón para crear un nuevo Contrato -->
        <a href="{{ route('contracts.create') }}" class="btn btn-primary">Nuevo Contrato</a>
        <!-- Formulario de filtro -->
        <form action="{{ route('contracts.index') }}" method="GET" class="d-flex w-50">
            <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary ms-2">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Tabla de contratos -->
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th><a href="{{ route('contracts.index', ['search' => request('search'), 'sort' => 'user_dni', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">DNI Usuario</a></th>
                <th><a href="{{ route('contracts.index', ['search' => request('search'), 'sort' => 'company_cif', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">CIF Empresa</a></th>
                <th><a href="{{ route('contracts.index', ['search' => request('search'), 'sort' => 'type', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">Tipo</a></th>
                <th><a href="{{ route('contracts.index', ['search' => request('search'), 'sort' => 'begin_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">Fecha Inicio</a></th>
                <th><a href="{{ route('contracts.index', ['search' => request('search'), 'sort' => 'end_date', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">Fecha Fin</a></th>
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            @forelse ($contracts as $contract)
            <tr>
                <td>{{ $contract->user_dni }}</td>
                <td>{{ $contract->company_cif }}</td>
                <td>{{ $contract->type }}</td>
                <td>{{ $contract->begin_date }}</td>
                <td>{{ $contract->end_date }}</td>
                <td>
                    <div class="d-flex flex-row gap-2">
                        <!-- Botón para ver (Icono) -->
                        <a href="{{ route('contracts.show', $contract) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>

                        <!-- Botón para editar (Icono) -->
                        <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- Botón para abrir la ventana modal de confirmación -->
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal-{{ $contract->id }}" >
                            <i class="fas fa-trash"></i>
                        </button>

                        <!-- Modal de confirmación -->
                        <div class="modal fade" id="confirmDeleteModal-{{ $contract->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que quieres eliminar el contrato de <strong>{{ $contract->user_dni }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <form id="deleteForm-{{ $contract->id }}" action="{{ route('contracts.delete', $contract->id) }}" method="POST">
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
                <td colspan="6" class="text-center">No se encontraron contratos.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Enlaces de paginación -->
    <div class="d-flex justify-content-center">
        {{ $contracts->appends(['sort' => $sort, 'direction' => $direction])->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
