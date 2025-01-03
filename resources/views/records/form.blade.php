@extends('layouts.app')

@section('content')
<div class="container my-3 mb-5 pb-5">
    <h1 class="mb-4">{{ $isEditing ? 'Editar Registro' : ($isViewing ? 'Ver Registro' : 'Nueva Registro') }}</h1>

    <form action="{{ $isEditing ? route('records.update', $record->id) : route('records.store') }}" method="POST">
        @csrf
        @if($isEditing)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contract_id" class="form-label">Contrato</label>
                    <select name="contract_id" id="contract_id" class="form-control" {{ $isViewing ? 'disabled' : '' }}>
                        <option value="">Seleccionar Contrato</option>
                        @foreach($contracts as $contract)
                            <option value="{{ $contract->id }}" 
                                {{ old('contract_id', isset($record) ? $record->contract_id : '') == $contract->id ? 'selected' : '' }}>
                                Contrato #{{ $contract->id }} - DNI: {{ $contract->user_dni }} - CIF: {{ $contract->company_cif }}
                            </option>
                        @endforeach
                    </select>
                    @error('contract_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="sign_time" class="form-label">Hora de Firma</label>
                    <input type="datetime-local" id="sign_time" name="sign_time" class="form-control" value="{{ $record->sign_time ?? old('sign_time') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('sign_time') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="latitude" class="form-label">Latitud</label>
                    <input type="text" id="latitude" name="latitude" class="form-control" value="{{ $record->latitude ?? old('latitude') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('latitude') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="longitude" class="form-label">Longitud</label>
                    <input type="text" id="longitude" name="longitude" class="form-control" value="{{ $record->longitude ?? old('longitude') }}" {{ $isViewing ? 'disabled' : '' }}>
                    @error('longitude') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="finished" class="form-label">Finalizado</label>
                    <select name="finished" id="finished" class="form-control" {{ $isViewing ? 'disabled' : '' }}>
                        <option value="1" {{ old('finished', $record->finished ?? '') == 1 ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ old('finished', $record->finished ?? '') == 0 ? 'selected' : '' }}>No</option>
                    </select>
                    @error('finished') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="form-group text-center mt-3">
            @if(!$isViewing)
                <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Actualizar Registro' : 'Crear Registro' }}</button>
            @else
                <a href="{{ route('records.index') }}" class="btn btn-primary">Volver</a>
            @endif
        </div>
    </form>
</div>
@endsection
