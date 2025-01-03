@extends('layouts.app')

@section('content')
<div class="container my-3 mb-5 pb-5">
    <h1 class="mb-4">Empleados</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-between mb-4">
        <!-- Botón para crear un nuevo empleado -->
        <a href="{{ route('employees.create') }}" class="btn btn-primary">Nuevo Empleado</a>
        <!-- Formulario de filtro -->
        <form action="{{ route('employees.index') }}" method="GET" class="d-flex w-50">
            <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary ms-2">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-primary"> <!-- Fondo azul y texto blanco -->
            <tr>
                <th><a href="{{ route('employees.index', ['search' => request('search'), 'sort' => 'name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">Name</a></th>
                <th><a href="{{ route('employees.index', ['search' => request('search'), 'sort' => 'first_surname', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">First Surname</a></th>
                <th><a href="{{ route('employees.index', ['search' => request('search'), 'sort' => 'second_surname', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">Second Surname</a></th>
                <th><a href="{{ route('employees.index', ['search' => request('search'), 'sort' => 'email', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">Email</a></th>
                <th><a href="{{ route('employees.index', ['search' => request('search'), 'sort' => 'phone', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">Phone</a></th>
                <th><a href="{{ route('employees.index', ['search' => request('search'), 'sort' => 'dni', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">DNI</a></th>
                <th><a href="{{ route('employees.index', ['search' => request('search'), 'sort' => 'city', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">City</a></th>
                <th><a href="{{ route('employees.index', ['search' => request('search'), 'sort' => 'register_date', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}">Register Date</a></th>
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->first_surname }}</td>
                <td>{{ $employee->second_surname }}</td>
                <td>{{ $employee->email }}</td>
                <td>{{ $employee->phone }}</td>
                <td>{{ $employee->dni }}</td>
                <td>{{ $employee->city }}</td>
                <td>{{ $employee->register_date }}</td>
                <td>
                    <div class="d-flex flex-row gap-2">
                        <!-- Botón para ver (Icono) -->
                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>

                        <!-- Botón para editar (Icono) -->
                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <!-- Botón para abrir la ventana modal de confirmación -->
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal-{{ $employee->id }}" >
                            <i class="fas fa-trash"></i>
                        </button>

                        <!-- Modal de confirmación -->
                        <div class="modal fade" id="confirmDeleteModal-{{ $employee->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que quieres eliminar al empleado con DNI <strong>{{ $employee->dni }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <form id="deleteForm-{{ $employee->id }}" action="{{ route('employees.delete', $employee->id) }}" method="POST">
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
                <td colspan="6" class="text-center">No se encontraron empleados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Enlaces de paginación -->
    <div class="d-flex justify-content-center">
        {{ $employees->appends(['sort' => $sort, 'direction' => $direction])->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection