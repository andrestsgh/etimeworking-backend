@extends('layouts.app')

@section('content')
<div class="container my-3 mb-5 pb-5">

    <h1 class="mb-4">{{ $isEditing ? 'Editar Empresa' : ($isViewing ? 'Ver Empresa' : 'Nueva Empresa') }}</h1>

    <form method="POST" action="{{ $isEditing ? route('companies.update', $company->id) : route('companies.store') }}">
        @csrf
        @if($isEditing)
            @method('PUT')
        @endif

        <div class="row">
            <!-- Nombre (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $company->name ?? old('name') }}" {{ $isViewing ? 'disabled' : '' }} @if(!$isEditing) required @endif>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="city" class="form-label">Ciudad</label>
                    <input type="text" id="city" name="city" class="form-control" value="{{ $company->city ?? old('city') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>


        <div class="row">
            <!-- País (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="country" class="form-label">País</label>
                    <select id="country" name="country" class="form-control" {{ $isViewing ? 'disabled' : '' }}>
                        @foreach($countries as $option)
                            <option value="{{ $option }}" {{ (old('country', $contract->country ?? '') == $option) ? 'selected' : '' }}>
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                    @error('country') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ $company->email ?? old('email') }}" {{ $isViewing ? 'disabled' : '' }} @if(!$isEditing) required @endif>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Teléfono (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ $company->phone ?? old('phone') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cif" class="form-label">CIF</label>
                    <input type="text" id="cif" name="cif" class="form-control" value="{{ $company->cif ?? old('cif') }}" {{ $isViewing ? 'disabled' : '' }} @if(!$isEditing) required @endif>
                    @error('cif') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Dirección (obligatorio) -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address" class="form-label">Dirección</label>
                    <textarea id="address" name="address" class="form-control" {{ $isViewing ? 'disabled' : '' }}>{{ $company->address ?? old('address') }}</textarea>
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>
        <div class="form-group text-center mt-3">
            @if(!$isViewing)
                <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Actualizar Empresa' : 'Crear Empresa' }}</button>
            @else
                <a href="{{ route('companies.index') }}" class="btn btn-primary">Volver</a>
            @endif
        </div>
    </form>
</div>
@endsection
