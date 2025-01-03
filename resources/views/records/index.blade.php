@extends('layouts.app')

@section('content')
<div class="container my-3 mb-5 pb-5">
    <h1 class="mb-4">Registros</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="d-flex justify-content-between mb-4">
        <!-- Botón para crear un nuevo registro -->
        <a href="{{ route('records.create') }}" class="btn btn-primary">Nuevo Registro</a>
        <!-- Formulario de filtro -->
        <form action="{{ route('records.index') }}" method="GET" class="d-flex w-50">
            <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary ms-2">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Tabla de registros -->
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th>Contrato</th>
                <th>DNI</th>
                <th>CIF</th>
                <th>Latitud</th>
                <th>Longitud</th>                
                <th>Hora de Firma</th>
                <th>Finalizado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr>
                    <td>{{ $record->contract->id }}</td>
                    <td>{{ $record->contract->user_dni }}</td>
                    <td>{{ $record->contract->company_cif }}</td>
                    <td>{{ $record->latitude }}</td>
                    <td>{{ $record->longitude }}</td>
                    <td>{{ $record->sign_time }}</td>
                    <td>{{ $record->finished ? 'Sí' : 'No' }}</td>
                    <td>
                        <div class="d-flex flex-row gap-2">
                            <!-- Botón para ver (Icono) -->
                            <a href="{{ route('records.show', $record) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
    
                            <!-- Botón para editar (Icono) -->
                            <a href="{{ route('records.edit', $record) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
    
                            <!-- Botón para abrir la ventana modal de confirmación -->
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal-{{ $record->id }}" >
                                <i class="fas fa-trash"></i>
                            </button>
    
                            <!-- Modal de confirmación -->
                            <div class="modal fade" id="confirmDeleteModal-{{ $record->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            ¿Estás seguro de que quieres eliminar el registro del contrato <strong>{{ $record->contract->id }}</strong> de las <strong>{{ $record->sign_time }} h.</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form id="deleteForm-{{ $record->id }}" action="{{ route('records.delete', $record->id) }}" method="POST">
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
                    <td colspan="5" class="text-center">No se encontraron registros</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Enlaces de paginación -->
    <div class="d-flex justify-content-center">
        {{ $records->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
