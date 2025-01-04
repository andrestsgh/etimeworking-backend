<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contract;
use Illuminate\Http\Request;

/***** CompanyController: Clase controller para las empresas */
class CompanyController extends Controller
{
    private $countries = [
        'España',
        'Estados Unidos',
        'Francia',
        'Alemania',
        'Italia',
    ];
    /***** index: Gestiona los resultados de la vista principal */
    public function index(Request $request){
        // Obtiene los parámetros de ordenación y búsqueda
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        $search = $request->get('search');

        // Realiza la consulta con los parámetros y devuelve los resultados paginados con los criterios seleccionados.
        // En un futuro parametizar la cantidad de resultados por página.
        $companies = Company::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('city', 'like', "%{$search}%")
                         ->orWhere('country', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('cif', 'like', "%{$search}%");
        })
        ->orderBy($sort, $direction)
        ->paginate(10)->appends(['search' => $search, 'sort' => $sort, 'direction' => $direction]);;

        return view('companies.index', compact('companies', 'sort', 'direction', 'search'));
    }

    /***** create: llama a la vista formulario para crear una empresa */
    public function create(){
        // Carga la vista del formulario. Los valores isEditing e isViewing indican si esta visualizando, editando o creando en caso de que ambos sean false.
        return view('companies.form', ['company' => null, 'isViewing' => false, 'isEditing' => false, 'countries' => $this->countries]);
    }

    /***** edit: llama a la vista formulario para editar una empresa */
    public function edit(Company $company){
        return view('companies.form', ['company' => $company, 'isViewing' => false, 'isEditing' => true, 'countries' => $this->countries]);
    }

    /***** show: muestra los datos de una empresa, sin posibilidad de edición */
    public function show(Company $company){
        return view('companies.form', ['company' => $company, 'isViewing' => true, 'isEditing' => false, 'countries' => $this->countries]);
    }

    /***** delete: elimina una empresa utilizando su id */
    public function delete($id){
        // Busca la empresa y en caso de no encontrarla devuelve fallo
        $company = Company::findOrFail($id);

        // Elimina la empresa en caso de encontrarla
        $company->delete();
        // Recarga la vista la vista del listado y devuelve un mensaje
        return redirect()->route('companies.index')->with('success', 'Empresa eliminada correctamente.');
    }

    /***** validate: Valida los campos requeridos y formatos de una empresa */
    public function validate($request){
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'email' => 'required|email|unique:companies',
            'phone' => 'nullable|string|max:20',
            'cif' => 'required|string|max:9|unique:companies',
            'address' => 'nullable|string',
        ]);
    }

    /***** store: almacena una empresa */
    public function store(Request $request){
        $this->validate($request);

        // Crea la empresa
        Company::create($request->all());

        // Carga de nuevo la vista del listado de empresas devolviendo un mensaje de feedback
        return redirect()->route('companies.index')->with('success', 'Empresa creada correctamente.');
    }

    /***** update: actualiza un contrato */
    public function update(Request $request, $id){
        $company = Company::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'phone' => 'nullable|string|max:20',
            'cif' => 'required|string|max:9|unique:companies,cif,' . $company->id,
            'address' => 'nullable|string',
        ]);

        // Almacena el CIF antiguo
        $old_cif = $company->cif;

        // Si el CIF ha cambiado, actualiza los contratos asociados
        if ($old_cif !== $request->cif) {
            Contract::where('company_cif', $old_cif)->update(['company_cif' => $request->cif]);
        }
        // Actualiza la empresa
        $company->update($request->all());

        // Carga de nuevo la vista del listado de empresas devolviendo un mensaje de feedback
        return redirect()->route('companies.index')->with('success', 'Empresa actualizada correctamente.');
    }
}
