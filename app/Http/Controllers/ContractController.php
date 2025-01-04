<?php
namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;

/***** ContractController: Clase controller para los contratos */
class ContractController extends Controller
{
    /***** index: Gestiona los resultados de la vista principal */
    public function index(Request $request){
        // Obtiene los parámetros de ordenación y búsqueda
        $sort = $request->get('sort', 'user_dni');
        $direction = $request->get('direction', 'asc');
        $search = $request->get('search');

        // Realiza la consulta con los parámetros y devuelve los resultados paginados con los criterios seleccionados.
        // En un futuro parametizar la cantidad de resultados por página.
        $contracts = Contract::when($search, function ($query) use ($search) {
                return $query->where('user_dni', 'like', "%{$search}%")
                            ->orWhere('company_cif', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%")
                            ->orWhere('begin_date', 'like', "%{$search}%")
                            ->orWhere('end_date', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction)
            ->paginate(10)->appends(['search' => $search, 'sort' => $sort, 'direction' => $direction]);

        return view('contracts.index', compact('contracts','sort','direction','search'));
    }

    /***** create: llama a la vista formulario para crear contrato */
    public function create(){
        // Recoge todos los usuarios y compañias necesarios para listarlos en el formulario.
        $users = User::all();
        $companies = Company::all();
        // Carga la vista del formulario. Los valores isEditing e isViewing indican si esta visualizando, editando o creando en caso de que ambos sean false.
        return view('contracts.form', ['isEditing' => false, 'isViewing' => false, 'users' => $users, 'companies' => $companies]);
    }

    /***** edit: llama a la vista formulario para editar contrato */
    public function edit(Contract $contract){
        $users = User::all();
        $companies = Company::all();
        return view('contracts.form', ['isEditing' => true, 'isViewing' => false, 'contract' => $contract, 'users' => $users, 'companies' => $companies]);
    }

    /***** show: muestra los datos de un contrato, sin posibilidad de edición */
    public function show(Contract $contract){
        // Recoge todos los usuarios y compañias necesarios para listarlos en el formulario.
        $users = User::all();
        $companies = Company::all();
        return view('contracts.form', ['isEditing' => false, 'isViewing' => true, 'contract' => $contract, 'users' => $users, 'companies' => $companies]);
    }

    /***** delete: elimina un contrato utilizando su id */
    public function delete($id){
        // Busca el contrato y en caso de no encontrarlo devuelve fallo
        $contract = Contract::findOrFail($id);

        // Elimina el contrato en caso de encontrarlo
        $contract->delete();
        // Recarga la vista la vista del listado y devuelve un mensaje
        return redirect()->route('contracts.index')->with('success', 'Contrato eliminado correctamente.');
    }

    /***** validate: Rellena los campos id's de la relación y valida los campos requeridos y formatos de un contrato */
    public function validate($request){

        // Obtiene el id del usuario a partir del dni
        $user = User::where('dni', $request->input('user_dni'))->first();

        // Obtiene el id de la empresa a partir del cif
        $company = Company::where('cif', $request->input('company_cif'))->first();

        // Agrega los id's al request
        $request->merge([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);

        $request->validate([
            'user_id' => 'required|integer',
            'company_id' => 'required|integer',
            'user_dni' => 'required|exists:users,dni',
            'company_cif' => 'required|exists:companies,cif',
            'type' => 'required|in:Indefinido,Temporal,Discontinuo', // Comprueba el conjunto de valores válidos
            'begin_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:begin_date', // La fecha de fin debe ser posterior a la de inicio
            'hours' => 'required|integer|min:1',
            'periodicity' => 'required|in:daily,weekly,monthly', // Comprueba el conjunto de valores válidos
            'job_position' => 'required|string|max:255',
        ]);
    }

    /***** store: almacena un contrato */
    public function store(Request $request){
        $this->validate($request);

        // Crear el contrato
        Contract::create($request->all());

        // Carga de nuevo la vista del listado de contratos devolviendo un mensaje de feedback
        return redirect()->route('contracts.index')->with('success', 'Contrato creado correctamente.');
    }

    /***** update: actualiza un contrato */
    public function update(Request $request, Contract $contract){
        $this->validate($request);

        // Actualiza el contrato
        $contract->update($request->all());
        
        // Carga de nuevo la vista del listado de contratos devolviendo un mensaje de feedback
        return redirect()->route('contracts.index')->with('success', 'Contrato actualizado correctamente.');
    }
}

