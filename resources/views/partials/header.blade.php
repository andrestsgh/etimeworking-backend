<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('assets/images/etimeworking_logo.png') }}" alt="Logo" width="40" height="40" class="d-inline-block align-text-top">
            E - Time Working
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto text-end">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}" onclick="showLoadingSpinner()">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('companies.index') }}" onclick="showLoadingSpinner()">Empresas</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('employees.index') }}" onclick="showLoadingSpinner()">Empleados</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contracts.index') }}" onclick="showLoadingSpinner()">Contratos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('records.index') }}" onclick="showLoadingSpinner()">Informes</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('profile') }}" onclick="showLoadingSpinner()">Perfil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('configuration') }}" onclick="showLoadingSpinner()">Configuración</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="{{ route('logout') }}" onclick="showLoadingSpinner()">Salir</a></li>
            </ul>
        </div>
    </div>
</nav>
