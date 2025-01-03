<?php

namespace App\Http\Controllers;

use App\Models\Record;
use App\Models\Contract;
use Illuminate\Http\Request;

/***** RecordController: Clase controller para los registros */
class RecordController extends Controller
{
    /***** index: Gestiona los resultados de la vista principal */
    public function index(Request $request)
    {
        // Obtiene los parámetros de búsqueda
        $search = $request->input('search');
        
        // Dado que el dni y el cif no pertenecen al modelo Record, para permitir al usuario filtrar se realiza en dos pasos.
        // Devuelve los registos con los campos user_dni y company_cif del contrato.
        $query = Record::with('contract')
            ->orderBy('contract_id')
            ->orderBy(Contract::select('user_dni')->whereColumn('contracts.id', 'records.contract_id'))
            ->orderBy(Contract::select('company_cif')->whereColumn('contracts.id', 'records.contract_id'))
            ->orderBy('sign_time');

        // Si existe un valor de búsqueda filtra los resultados.
        if ($search) {
            $query->whereHas('contract', function ($q) use ($search) {
                $q->where('user_dni', 'like', "%{$search}%")
                ->orWhere('company_cif', 'like', "%{$search}%")
                ->orWhere('sign_time', 'like', "%{$search}%");
            });
        }

        // Pagina los resultados obtenidos pasándo el campo de búsqueda
        $records = $query->paginate(10)->appends(['search' => $search]);

        // Devuelve la vista del listado de registros
        return view('records.index', compact('records', 'search'));
    }

    /***** create: llama a la vista formulario para crear un registro */
    public function create()
    {
        // Recoge todos los contratos necesarios para listarlos en el formulario.
        $contracts = Contract::all();
        // Carga la vista del formulario. Los valores isEditing e isViewing indican si esta visualizando, editando o creando en caso de que ambos sean false.
        return view('records.form', ['isEditing' => false, 'isViewing' => false, 'contracts' => $contracts]);
    }

    /***** edit: llama a la vista formulario para editar el registro */
    public function edit(Record $record)
    {
        $contracts = Contract::all();
        return view('records.form', ['isEditing' => true, 'isViewing' => false, 'record' => $record, 'contracts' => $contracts]);
    }

    /***** show: muestra los datos de un registro, sin posibilidad de edición */
    public function show(Record $record)
    {
        $contracts = Contract::all();
        return view('records.form', ['isEditing' => false, 'isViewing' => true, 'record' => $record, 'contracts' => $contracts]);
    }

    /***** delete: elimina un registro utilizando su id */
    public function delete($id)
    {
        // Busca el registro y en caso de no encontrarlo devuelve fallo
        $record = Record::findOrFail($id);
        // Elimina el contrato en caso de encontrarlo
        $record->delete();
        // Recarga la vista la vista del listado y devuelve un mensaje
        return redirect()->route('records.index')->with('success', 'Registro eliminado correctamente.');
    }

    /***** validate: Valida los campos requeridos y formatos de un registro */
    public function validate($request)
    {
        $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'sign_time' => 'required|date',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'finished' => 'required|boolean',
        ]);        
    }

    /***** store: almacena un registro */
    public function store(Request $request)
    {
        $this->validate($request);

        // Crea el registro
        Record::create($request->all());

        // Carga de nuevo la vista del listado de registros devolviendo un mensaje de feedback
        return redirect()->route('records.index')->with('success', 'Registro creado correctamente.');
    }

    /***** update: actualiza un registro */
    public function update(Request $request, Record $record)
    {
        $this->validate($request);

        // Actualiza el registro
        $record->update($request->all());

        // Carga de nuevo la vista del listado de registros devolviendo un mensaje de feedback
        return redirect()->route('records.index')->with('success', 'Registro actualizado correctamente.');
    }

    /*********** A PARTIR DE ESTE PUNTO LAS LLAMADAS PERTENECEN A LA API */
    
    /***** store: almacena un registro */
    public function storeRecord(Request $request) {
        // Valida los datos recibidos
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'finished' => 'required|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Crea el nuevo registro en la tabla 'records'
        $record = new Record([
            'contract_id' => $validated['contract_id'],
            'finished' => $validated['finished'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            // El campo sign_time se asignará automáticamente gracias al valor por defecto en la base de datos
        ]);

        // Guardar el nuevo registro. En caso correcto devuelve un mensaje con OK
        if ($record->save()) {
            $record->refresh();
            return response()->json([
                'success' => true,
                'message' => 'Registro creado correctamente.',
                'data' => $record,
            ], 200);
        } else { // En caso contrario devuelve un mensaje con código 500 (Server error)
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el registro.',
            ], 500); 
        }
    }
}
