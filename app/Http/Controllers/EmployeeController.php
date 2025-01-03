<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Record;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

/***** EmployeeController: Clase controller para los empleados */
class EmployeeController extends Controller
{
    /***** index: Gestiona los resultados de la vista principal */
    public function index(Request $request) {
        // Obtiene los parámetros de ordenación y búsqueda
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        $search = $request->get('search');
    
        // Realiza la consulta con los parámetros y devuelve los resultados paginados con los criterios seleccionados.
        // En un futuro parametizar la cantidad de resultados por página.
        $employees = User::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('first_surname', 'like', "%{$search}%")
                         ->orWhere('second_surname', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('dni', 'like', "%{$search}%")
                         ->orWhere('register_date', 'like', "%{$search}%")
                         ->orWhere('city', 'like', "%{$search}%");
        })
        ->orderBy($sort, $direction)
        ->paginate(10)->appends(['search' => $search, 'sort' => $sort, 'direction' => $direction]);
    
        // Devuelve la vista con los empleados, el orden y el término de búsqueda
        return view('employees.index', compact('employees', 'sort', 'direction', 'search'));
    }

    /***** create: llama a la vista formulario para crear un usuario */
    public function create() {
        // Carga la vista del formulario. Los valores isEditing e isViewing indican si esta visualizando, editando o creando en caso de que ambos sean false.
        return view('employees.form', ['employee' => null, 'isViewing' => false, 'isEditing' => false]);
    }

    /***** edit: llama a la vista formulario para editar un usuario */
    public function edit(User $employee) {
        return view('employees.form', ['employee' => $employee, 'isViewing' => false, 'isEditing' => true]);
    }

    /***** show: muestra los datos de un usuario, sin posibilidad de edición */
    public function show(User $employee) {
        return view('employees.form', ['employee' => $employee, 'isViewing' => true, 'isEditing' => false]);
    }

    /***** delete: elimina un usuario utilizando su id */
    public function delete($id) {
        // Busca el usuario y en caso de no encontrarlo devuelve fallo
        $employee = User::find($id);
        
        // Elimina el usuario en caso de encontrarlo
        $employee->delete();
        // Recarga la vista la vista del listado y devuelve un mensaje
        return redirect()->route('employees.index')->with('success', 'Usuario eliminado correctamente.');
    }

    /***** store: almacena un usuario */
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'first_surname' => 'required|string|max:255',
            'dni' => 'required|string|max:9|unique:users',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:employee,admin',
            'password' => 'required|string|min:6|confirmed',
            'url_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);
    
        $data = $request->all();
        
        // Subir la imagen si se selecciona una. La ruta es relativa, en la vista
        // se une con el nombre o ip del host
        if ($request->hasFile('url_picture')) {
            $path = $request->file('url_picture')->store('images', 'public');
            $data['url_picture'] = 'storage/images/' . basename($path);
        }
    
        // Crea el usuario. Los parámetros con ?? son opionales.
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'first_surname' => $data['first_surname'],
            'second_surname' => $data['second_surname'] ?? '',
            'dni' => $data['dni'],
            'phone' => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
            'city' => $data['city'] ?? '',
            'url_picture' => $data['url_picture'] ?? '',
            'role' => $data['role'],
            'birthdate' => $data['birthdate'] ?? null,
            'register_date' => now(),
        ]);
    
        // Carga de nuevo la vista del listado de usuarios devolviendo un mensaje de feedback
        return redirect()->route('employees.index')->with('success', 'Empleado creado correctamente.');
    }
    

    /***** update: actualiza un usuario */
    public function update(Request $request, $id) {
        $employee = User::findOrFail($id);
    
        // Validación de los campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string',
            'dni' => 'required|string|max:9|unique:users,dni,' . $employee->id,
            'first_surname' => 'required|string|max:255',
            'second_surname' => 'nullable|string|max:255',
            'role' => 'required|in:employee,admin',
            'birthdate' => 'nullable|date',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'url_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Valida la contraseña solo si es proporcionada
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Almacena el CIF antiguo
        $old_dni = $employee->dni;
        
        // Si el CIF ha cambiado, actualiza los contratos asociados
        if ($old_dni !== $request->dni) {
            Contract::where('user_dni', $old_dni)->update(['user_dni' => $request->dni]);
        }

        // Actualizar los datos del empleado. Los parámetros con ?? son opionales.
        $employee->name = $request->input('name');
        $employee->email = $request->input('email');
        $employee->phone = $request->input('phone') ?? '';
        $employee->dni = $request->input('dni');
        $employee->first_surname = $request->input('first_surname');
        $employee->second_surname = $request->input('second_surname') ?? '';
        $employee->role = $request->input('role');
        $employee->birthdate = $request->input('birthdate') ?? null;
        $employee->address = $request->input('address') ?? '';
        $employee->city = $request->input('city') ?? '';
    
        // Si el usuario ha subido una nueva imagen, la guardamos en el servidor
        if ($request->hasFile('url_picture')) {
            // Elimina la imagen anterior si existe
            if ($employee->url_picture && file_exists(public_path($employee->url_picture))) {
                unlink(public_path($employee->url_picture));
            }
            // Guarda la nueva imagen. La ruta es relativa
            $imagePath = $request->file('url_picture')->store('images', 'public');
            $employee->url_picture = 'storage/images/' . basename($imagePath);
        }
    
        // Si la contraseña se ha cambiado se actualiza
        if ($request->filled('password')) {
            $employee->password = bcrypt($request->input('password'));
        }
    
        // Actualiza el ususrio
        $employee->save();
    
        // Carga de nuevo la vista del listado de usuarios devolviendo un mensaje de feedback
        return redirect()->route('employees.index')->with('success', 'Empleado actualizado con éxito.');
    }
    

    /********************* A PARTIR DE ESTE PUNTO COMIENZAN LOS MÉTODOS UTILIZADOS POR LAS LLAMADAS A LA API */


    /***** getEmployeeContracts: devuelve TODOS los contratos de un usuario */
    public function getEmployeeContracts(Request $request) {
        // Usuario autenticado
        $user = $request->user();

        // Contratos asociados al usuario
        $contracts = Contract::where('user_dni', $user->dni)->get();
        
        // Si no se encuentra el contrato, devuelve un mensaje error con (404) (Not Found)
        if (!$contracts) {
            return response()->json([
                'success' => false,
                'message' => 'Contrato no encontrado o no pertenece al usuario.',
            ], 404);
        }
        
        // Devuelve los contratos del usuario con (200) OK.
        return response()->json([
            'success' => true,
            'data' => $contracts,
        ], 200);
    }

    /***** getEmployeeActiveContracts: devuelve los contratos vigentes de un usuario. 
     * Aquellos contratos que la fecha de finalización es posterior a la actual
     * Uso de la librería Carbon para la gestión de fechas.
     * Esta llamada se utiliza para fichar, por tanto se necesita saber las horas trabajadas */
    public function getEmployeeActiveContracts(Request $request) {
        // Usuario autenticado
        $user = $request->user();

        // Contratos activos asociados al usuario
        $contracts = Contract::where('user_dni', $user->dni)
            ->where(function ($query) {
                $query->whereNull('end_date') // Contratos sin fecha de finalización
                    ->orWhere('end_date', '>=', Carbon::now()); // Contratos vigentes
            })
            ->get();
        
        // Si no se encuentra el contrato, devuelve un mensaje error con (404) (Not Found)
        if (!$contracts) {
            return response()->json([
                'success' => false,
                'message' => 'Contrato no encontrado o no pertenece al usuario.',
            ], 404);
        }
        
        // Por cada contrato calcula las horas trabajadas según la jornada del trabajador
        $contractsWithHours = $contracts->map(function ($contract) {
            // Cálculo de las fechas de inicio y fin dependiendo de la periodicidad del contrato
            $startDate = null;
            $endDate = null;

            switch (strtolower($contract->periodicity)) {
                case 'daily':
                    $startDate = Carbon::now()->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
                    break;
                case 'weekly':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;
                case 'monthly':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
                default:
                    $startDate = Carbon::now()->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
                    break;
            }
            return $this->searchContractWithRecords($contract->user_dni, $contract->id, $startDate, $endDate);
        });

        // Devuelve los contratos con los datos solicitados
        return response()->json([
            'success' => true,
            'data' => $contractsWithHours,
        ], 200);
    }

    
    /***** searchContractWithRecords: Busca los registros de un contrato entre fechas y devuelve los resultados paginados.
     * Por defecto sólo calcula las horas, no incluye los registros en el contrato, a no ser que se solicite. */
    private function searchContractWithRecords($user_dni,$contract_id, $startRecords, $endRecords, $includeRecords = false, $records_per_page = 10, $page = 1) {
        
        // Si se solicita incluir los registros en el contrato (esto sólo es usado para el informe de búsqueda de registros en un contrato)
        if ($includeRecords){
            // Obtiene el contrato asociado al usuario, incluyendo los registros de la jornada (diaria, semanal o mensual)
            $contract = Contract::where('user_dni', $user_dni)
                                ->where('id', $contract_id)
                                ->with(['records' => function ($query) use ($startRecords, $endRecords, $records_per_page, $page) {
                                    $query->whereBetween('sign_time', [$startRecords, $endRecords])
                                          ->paginate($records_per_page, ['*'], 'page', $page);
                                }])
                                ->first();
        }else{ // En caso contrario se devuelven todos los registros de la jornada, ya que se usará para calcular las horas, no para el informe de registros
            $contract = Contract::where('user_dni', $user_dni)
                                ->where('id', $contract_id)
                                ->with(['records' => function ($query) use ($startRecords, $endRecords) {
                                    $query->whereBetween('sign_time', [$startRecords, $endRecords]);
                                }])
                                ->first();
        }
        // Si no se encuentra el contrato, devuelve null, ya que este método no devuelve una respuesta http
        if (!$contract) {
            return null;
        }

        $contract->startRecords = $startRecords;
        $contract->endRecords = $endRecords;

        // Si interesa tener los registros en el contrato devuelto se devuelve el contrato.
        // Si por el contrario lo que interesa son las horas calculadas de la jornada, se devuelven sólo las horas sin los registros
        if ($includeRecords){
            return $contract;
        } else {
            // Devolver el contrato encontrado con las horas calculadas
            return $this->computeContract($contract);
        }
    }

    /***** computeContract: Devuelve el contrato, sin incluir registros, con las horas totales cumplidas en la jornada. */
    private function computeContract($contract) {
        $totalMinutes = 0;

        // Ordena por fecha, ya que si se inicia una jornada, si existe un registro a continuación debe ser de finalización de jornada.
        $records = $contract->records->sortBy('sign_time');
        $currentStart = null;
        $record = null;
        // Calcula los intervalos de tiempo en minutos entre el registro de inicio y fin de jornada
        foreach ($records as $record) {
            if (!$currentStart && !$record->finished) {
                $currentStart = $record->sign_time;
            } elseif ($currentStart && $record->finished) {
                $start = Carbon::parse($currentStart);
                $end = Carbon::parse($record->sign_time);
                $totalMinutes += $start->diffInMinutes($end);
                $currentStart = null;
            }
        }

        // Añadir los campos calculados al contrato
        //$contract->hours_worked = round($totalMinutes / 60, 2); // Convierte a horas
        $contract->hours_worked = $totalMinutes; // Lo dejo sin convertir a horas para hacer pruebas
        $contract->record = $record; // Incluye el último registro con la fecha y hora como feedback para visualizarlo en la APP
        // Elimina los registros del contrato, ya que no son necesarios.
        unset($contract->records);
        return $contract;
    }

    /***** getContract: Devuelve el contrato con los registros paginados, totales o entre dos fechas. Usado para el informe de registros */
    public function getContract(Request $request, $contract_id, $start_date = null, $end_date = null, $records_per_page = 10, $page = 1) {
        $includeRecords = ($start_date === null) ? false : true;
        // Usuario autenticado
        $user = $request->user();

        // Obtiene el contrato asociado al usuario
        $contract = Contract::where('user_dni', $user->dni)
                            ->where('id', $contract_id)
                            ->first();

        // Si no se encuentra el contrato, devuelve un mensaje error con (404) (Not Found)
        if (!$contract) {
            return response()->json([
                'success' => false,
                'message' => 'Contrato no encontrado o no pertenece al usuario.',
            ], 404);
        }

        // Si existe fecha de inicio y fin se convierte al formato de Carbon
        if ($start_date && $end_date) {
            try {
                $startDate = Carbon::parse($start_date)->startOfDay();
                $endDate = Carbon::parse($end_date)->endOfDay();
            } catch (\Exception $e) {   // En caso de formato de fecha incorrecto
                return response()->json([
                    'success' => false,
                    'message' => 'Formato de fecha inválido.',
                ], 400); // Respuesta con código HTTP 400 (Bad Request)
            }
        } else {   // Si no se proporcionan fechas se calcula la fecha de inicio y fin basándose en la periodicidad de la jornada
            switch (strtolower($contract->periodicity)) {
                case 'daily':
                    $startDate = Carbon::now()->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
                    break;
                case 'weekly':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;
                case 'monthly':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
                default:
                    $startDate = Carbon::now()->startDay();
                    $endDate = Carbon::now()->endDay();
                    break;
            }
        }
        // Obtiene los registros del contrato entre fechas
        $contract = $this->searchContractWithRecords($user->dni, $contract_id, $startDate, $endDate, $includeRecords,$records_per_page,$page);
        
        // Si no se encuentra el contrato, devuelve un mensaje con 404 (Not found)
        if (!$contract) {
            return response()->json([
                'success' => false,
                'message' => 'Contrato no encontrado o no pertenece al usuario.',
            ], 404);  // Respuesta con código HTTP 404 (No encontrado)
        }

        // Devuelve el contrato con los registros solicitados y el código de respuesta 200 (OK)
        return response()->json([
            'success' => true,
            'data' => $contract,
        ], 200);
    }
}
