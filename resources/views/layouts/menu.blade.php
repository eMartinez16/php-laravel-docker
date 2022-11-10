<li class="menu-header">Dashboard</li>
<li class="side-menus">
  <a class="nav-link" href="{{ route('home') }}">
    <i class="fas fa-tachometer-alt"></i><span>Dashboard</span>
  </a>
</li>
<li class="menu-header">Cartas de Porte</li>
<li class="side-menus">
  <a class="nav-link" href="{{ route('admin.carta_porte.indexAuto') }}">
    <i class="fas fa-truck"></i><span>Automotor</span>
  </a>
  <a class="nav-link" href="{{ route('admin.carta_porte.indexFerro') }}">
    <i class="fas fa-train"></i><span>Ferroviarias</span>
  </a>
  <a class="nav-link" href="{{ route('admin.carta_porte.reports') }}">
    <i class="fas fa-flag"></i><span>Reportes</span>
  </a>
</li>
<li class="menu-header">Clientes</li>
<li class="side-menus">
  <a class="nav-link" href="{{ route('admin.client_category.index') }}">
    <i class="fas fa-list"></i><span>Categorias</span>
  </a>
  <a class="nav-link" href="{{ route('admin.clients.index') }}">
    <i class="fas fa-users"></i><span>Clientes</span>
  </a>
</li>
<li class="menu-header">Ticket de descarga</li>
<li class="side-menus">
  <a class="nav-link" href="{{ route('admin.grains.index') }}">
    <i class="fas fa-seedling"></i><span>Granos</span>
  </a>
  <a class="nav-link" href="{{ route('admin.categories.index') }}">
    <i class="fas fa-seedling"></i><span>Rubros de granos</span>
  </a>
  <a class="nav-link" href="{{ route('admin.grainPercentages.index') }}">
    <i class="fas fa-percentage"></i><span>% de rebajas de granos</span>      
  </a>
  <a class="nav-link" href="{{ route('admin.ticket.index') }}">
    <i class="fas fa-file-download"></i><span>Ticket de descarga</span>
  </a>
</li>
<li class="menu-header">Reporte de cámara</li>
<li class="side-menus">
  <a class="nav-link" href="{{ route('admin.params.index') }}">
    <i class="fas fa-seedling"></i><span>Parámetros de granos</span>
  </a>
  <a class="nav-link" href="{{ route('admin.cameraReport.index') }}">
    <i class="fas fa-file"></i><span>Reporte de Cámara</span>
  </a>
</li>
<li class="menu-header">Gestión</li>
<li class="side-menus">
  <a class="nav-link" href="{{ route('admin.clients.payment.index') }}">
    <i class="far fa-money-bill-alt"></i><span>Cobro</span>
  </a>
  <a class="nav-link" href="{{ route('admin.rates.index') }}">
    <i class="fas fa-inbox"></i><span>Tarifas</span>
  </a>
  <a class="nav-link" href="{{ route('admin.clients.current-account.index') }}">
    <i class="fas fa-inbox"></i><span>Cuenta corriente</span>
  </a>
  <a class="nav-link" href="{{ route('admin.clients.debts.index') }}">
    <i class="fas fa-inbox"></i><span>Reporte de deuda</span>
  </a>
  <a class="nav-link" href="{{ route('admin.invoices.index') }}">
    <i class="fas fa-file-invoice-dollar"></i><span>Facturación</span>
  </a>
</li>
<li class="menu-header">Egresos</li>
<li class="side-menus">
  <a class="nav-link" href="{{ route('admin.provider.index') }}">
    <i class="fas fa-users"></i><span>Proveedores</span>
  </a>
  <a class="nav-link" href="{{ route('admin.cost-centers.index') }}">
    <i class="fas fa-align-center"></i><span>Centro de costos</span>
  </a>
</li>
@if(Auth::user()->role == 'administrador')
  <li class="menu-header">Accesos</li>
  <li class="side-menus">
    <a class="nav-link" href="{{ route('admin.usuario.index') }}">
      <i class="fas fa-users"></i><span>Usuarios</span>
    </a>
    <a class="nav-link" href="{{ route('admin.logs.index') }}">
      <i class="fas fa-solid fa-book"></i><span>Logs</span>
    </a>
  </li>
@endif