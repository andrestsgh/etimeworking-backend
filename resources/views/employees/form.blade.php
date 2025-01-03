@extends('layouts.app')

@section('content')
<div class="container my-3 mb-5 pb-5">
    <h1 class="mb-4">
        @if($employee)
            @if($isViewing)
                Ver Empleado
            @else
                Editar Empleado
            @endif
        @else
            Nuevo Empleado
        @endif
    </h1>

    <!-- Mostrar mensaje de éxito si existe -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="form-header">    
        <!-- Mostrar la foto solo si no estamos creando un nuevo empleado -->
        @if(isset($employee) && $employee->url_picture)
            <div class="user-photo">
                <img src="{{ asset('') . $employee->url_picture }}" 
                     alt="Foto del Usuario" 
                     class="img-thumbnail" 
                     style="max-width: 150px; max-height: 150px;">
            </div>
        @elseif(isset($employee))
            <!-- Si el campo está vacío, se muestra la imagen por defecto -->
            <div class="user-photo">
                <img src="http://laravel.local:8000/assets/images/user-default.png" 
                     alt="Foto por defecto" 
                     class="img-thumbnail" 
                     style="max-width: 150px; max-height: 150px;">
            </div>
        @endif
    </div>
    <!-- Formulario de empleado -->
    <form action="{{ $employee ? route('employees.update', $employee->id) : route('employees.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($employee)
            @method('PUT')
        @endif

        <div class="row">
            <!-- Nombre (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $employee ? $employee->name : '') }}" {{ $isViewing ? 'disabled' : '' }} required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Primer Apellido (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="first_surname">Primer Apellido <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="first_surname" id="first_surname" value="{{ old('first_surname', $employee ? $employee->first_surname : '') }}" {{ $isViewing ? 'disabled' : '' }} required>
                    @error('first_surname') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Segundo Apellido -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="second_surname">Segundo Apellido</label>
                    <input type="text" class="form-control" name="second_surname" id="second_surname" value="{{ old('second_surname', $employee ? $employee->second_surname : '') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('second_surname') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Correo Electrónico (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Correo Electrónico <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $employee ? $employee->email : '') }}" {{ $isViewing ? 'disabled' : '' }} required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Teléfono -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone', $employee ? $employee->phone : '') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- DNI (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="dni">DNI <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="dni" id="dni" value="{{ old('dni', $employee ? $employee->dni : '') }}" {{ $isViewing ? 'disabled' : '' }} required>
                    @error('dni') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Ciudad -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="city">Ciudad</label>
                    <input type="text" class="form-control" name="city" id="city" value="{{ old('city', $employee ? $employee->city : '') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Fecha de Nacimiento -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="birthdate">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" name="birthdate" id="birthdate" value="{{ old('birthdate', $employee ? $employee->birthdate : '') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('birthdate') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Contraseña (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Contraseña <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password" id="password" {{ $isViewing ? 'disabled' : '' }} @if(!$isEditing) required @endif>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <!-- Confirmar Contraseña (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password_confirmation">Confirmar Contraseña <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" {{ $isViewing ? 'disabled' : '' }} @if(!$isEditing) required @endif>
                    @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Role (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="role">Role <span class="text-danger">*</span></label>
                    <select class="form-control" name="role" id="role" {{ $isViewing ? 'disabled' : '' }} required>
                        <option value="employee" {{ old('role', $employee ? $employee->role : '') == 'employee' ? 'selected' : '' }}>Empleado</option>
                        <option value="admin" {{ old('role', $employee ? $employee->role : '') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Imagen de Perfil -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="url_picture">Imagen de Perfil</label>
                    <input type="file" class="form-control" name="url_picture" id="url_picture" {{ $isViewing ? 'disabled' : '' }}>
                    @error('url_picture') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Dirección -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Dirección</label>
                    <input type="text" class="form-control" name="address" id="address" value="{{ old('address', $employee ? $employee->address : '') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <!-- Fecha de Registro (solo lectura) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="register_date">Fecha de Registro</label>
                    <input type="text" class="form-control" name="register_date" id="register_date" value="{{ old('register_date', $employee ? $employee->register_date : '') }}" disabled>
                </div>
            </div>
        </div>
        <div class="form-group text-center mt-3">
            @if(!$isViewing)
                <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Actualizar Empleado' : 'Crear Empleado' }}</button>
            @else
                <a href="{{ route('employees.index') }}" class="btn btn-primary">Volver</a>
            @endif
        </div>
    </form>
</div>
@endsection
