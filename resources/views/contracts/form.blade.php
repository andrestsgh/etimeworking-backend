@extends('layouts.app')

@section('content')
<div class="container my-3 mb-5 pb-5">
    <h1 class="mb-4">{{ $isEditing ? 'Editar Contrato' : ($isViewing ? 'Ver Contrato' : 'Nuevo Contrato') }}</h1>

    <form method="POST" action="{{ $isEditing ? route('contracts.update', $contract->id) : route('contracts.store') }}">
        @csrf
        @if($isEditing)
            @method('PUT')
        @endif

        <div class="row">
            <!-- DNI (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="user_dni" class="form-label">DNI del Usuario</label>
                    <select id="user_dni" name="user_dni" class="form-control" {{ $isViewing ? 'disabled' : '' }}>
                        <option value="">Seleccionar usuario</option>
                        @foreach($users as $user)
                            <option value="{{ $user->dni }}" {{ (old('user_dni', $contract->user_dni ?? '') == $user->dni) ? 'selected' : '' }}>
                                {{ $user->dni }} - {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_dni') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="company_cif" class="form-label">CIF de la Empresa</label>
                    <select id="company_cif" name="company_cif" class="form-control" {{ $isViewing ? 'disabled' : '' }}>
                        <option value="">Seleccionar empresa</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->cif }}" {{ (old('company_cif', $contract->company_cif ?? '') == $company->cif) ? 'selected' : '' }}>
                                {{ $company->cif }} - {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_cif') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="begin_date" class="form-label">Fecha de Inicio</label>
                    <input type="date" id="begin_date" name="begin_date" class="form-control" value="{{ $contract->begin_date ?? old('begin_date') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('begin_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="end_date" class="form-label">Fecha de Fin</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $contract->end_date ?? old('end_date') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="type" class="form-label">Tipo</label>
                    <select id="type" name="type" class="form-control" {{ $isViewing ? 'disabled' : '' }}>
                        @foreach(['Indefinido', 'Temporal', 'Discontinuo'] as $option)
                            <option value="{{ $option }}" {{ (old('type', $contract->type ?? '') == $option) ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="hours" class="form-label">Horas</label>
                    <input type="number" id="hours" name="hours" class="form-control" value="{{ $contract->hours ?? old('hours') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('hours') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="periodicity" class="form-label">Periodicidad</label>
                    <select id="periodicity" name="periodicity" class="form-control" {{ $isViewing ? 'disabled' : '' }}>
                        @foreach(['daily', 'weekly', 'monthly'] as $option)
                            <option value="{{ $option }}" {{ (old('periodicity', $contract->periodicity ?? '') == $option) ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                    @error('periodicity') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="job_position" class="form-label">Puesto de Trabajo</label>
                    <input type="text" id="job_position" name="job_position" class="form-control" value="{{ $contract->job_position ?? old('job_position') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('job_position') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="form-group text-center mt-3">
            @if(!$isViewing)
                <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Actualizar Contrato' : 'Crear Contrato' }}</button>
            @else
                <a href="{{ route('contracts.index') }}" class="btn btn-secondary">Volver</a>
            @endif
        </div>
    </form>
</div>
@endsection
