<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - Sistema de Tickets</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            color: #333;
            font-size: 24px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .tabs {
            display: flex;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .tab {
            flex: 1;
            padding: 15px 20px;
            text-align: center;
            cursor: pointer;
            background: #f8f9fa;
            border: none;
            font-size: 16px;
            transition: all 0.3s;
        }

        .tab.active {
            background: #667eea;
            color: white;
        }

        .tab:hover:not(.active) {
            background: #e2e8f0;
        }

        .content-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
        }

        .btn-success {
            background: #48bb78;
            color: white;
        }

        .btn-success:hover {
            background: #38a169;
        }

        .btn-danger {
            background: #f56565;
            color: white;
        }

        .btn-danger:hover {
            background: #e53e3e;
        }

        .btn-warning {
            background: #ed8936;
            color: white;
        }

        .btn-warning:hover {
            background: #dd6b20;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }

        .users-table, .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .users-table th,
        .users-table td,
        .stats-table th,
        .stats-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .users-table th,
        .stats-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        .users-table tbody tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-activo {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-inactivo {
            background: #fed7d7;
            color: #c53030;
        }

        .role-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .role-admin {
            background: #e6fffa;
            color: #00695c;
        }

        .role-atencion {
            background: #e3f2fd;
            color: #1565c0;
        }

        .role-cajero {
            background: #f3e5f5;
            color: #6a1b9a;
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #feb2b2;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            color: #666;
            margin-top: 10px;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .tabs {
                flex-direction: column;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Administración del Sistema</h1>
        <div class="user-info">
            <span>Bienvenido, <strong id="userName">Administrador</strong></span>
            <button class="btn btn-secondary" onclick="logout()">Cerrar Sesión</button>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="tabs">
        <button class="tab active" onclick="cambiarTab('dashboard')">Dashboard</button>
        <button class="tab" onclick="cambiarTab('usuarios')">Gestión de Usuarios</button>
        <button class="tab" onclick="cambiarTab('tickets')">Gestión de Tickets</button>
        <button class="tab" onclick="cambiarTab('reportes')">Reportes</button>
    </div>

    <!-- Dashboard -->
    <div id="dashboard" class="content-section">
        <h2>Dashboard del Sistema</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" id="totalUsuarios">0</div>
                <div class="stat-label">Usuarios Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="totalTickets">0</div>
                <div class="stat-label">Tickets Totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="ticketsPendientes">0</div>
                <div class="stat-label">Tickets Pendientes</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="ticketsPagados">0</div>
                <div class="stat-label">Tickets Pagados</div>
            </div>
        </div>

        <h3>Actividad Reciente</h3>
        <table class="stats-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Último Acceso</th>
                    <th>Tickets Creados Hoy</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody id="actividadReciente">
                <!-- Se carga dinámicamente -->
            </tbody>
        </table>
    </div>

    <!-- Gestión de Usuarios -->
    <div id="usuarios" class="content-section hidden">
        <h2>Gestión de Usuarios</h2>
        
        <div style="margin-bottom: 20px;">
            <button class="btn btn-primary" onclick="mostrarModalUsuario()">Crear Nuevo Usuario</button>
            <button class="btn btn-secondary" onclick="cargarUsuarios()">Actualizar Lista</button>
        </div>

        <table class="users-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha Creación</th>
                    <th>Último Acceso</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="usuariosTableBody">
                <!-- Los usuarios se cargarán aquí -->
            </tbody>
        </table>
    </div>

    <!-- Gestión de Tickets -->
    <div id="tickets" class="content-section hidden">
        <h2>Gestión de Tickets</h2>
        
        <div style="margin-bottom: 20px;">
            <input type="text" id="searchTicket" placeholder="Buscar por paciente o cédula..." style="width: 300px; display: inline-block;">
            <button class="btn btn-primary" onclick="buscarTickets()">Buscar</button>
            <button class="btn btn-secondary" onclick="cargarTodosTickets()">Ver Todos</button>
        </div>

        <div style="max-height: 500px; overflow-y: auto;">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Cédula</th>
                        <th>Monto USD</th>
                        <th>Estado</th>
                        <th>Creado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="ticketsTableBody">
                    <!-- Los tickets se cargarán aquí -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Reportes -->
    <div id="reportes" class="content-section hidden">
        <h2>Reportes del Sistema</h2>
        
        <div class="form-grid">
            <div class="form-group">
                <label for="fechaInicio">Fecha Inicio:</label>
                <input type="date" id="fechaInicio">
            </div>
            <div class="form-group">
                <label for="fechaFin">Fecha Fin:</label>
                <input type="date" id="fechaFin">
            </div>
        </div>
        
        <div style="margin: 20px 0;">
            <button class="btn btn-primary" onclick="generarReporte()">Generar Reporte</button>
            <button class="btn btn-success" onclick="exportarReporte()">Exportar CSV</button>
        </div>

        <div id="reporteResultados">
            <!-- Los resultados del reporte se mostrarán aquí -->
        </div>
    </div>

    <!-- Modal para crear/editar usuario -->
    <div id="usuarioModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModalUsuario()">&times;</span>
            <h2 id="modalTitle">Crear Nuevo Usuario</h2>
            <form id="usuarioForm">
                <input type="hidden" id="usuarioId">
                
                <div class="form-group">
                    <label for="usuarioNombre">Usuario:</label>
                    <input type="text" id="usuarioNombre" name="usuario" required>
                </div>
                
                <div class="form-group">
                    <label for="nombreCompleto">Nombre Completo:</label>
                    <input type="text" id="nombreCompleto" name="nombre" required>
                </div>
                
                <div class="form-group">
                    <label for="rolUsuario">Rol:</label>
                    <select id="rolUsuario" name="rol" required>
                        <option value="">Seleccionar rol...</option>
                        <option value="admin">Administrador</option>
                        <option value="atencion">Atención Integral</option>
                        <option value="cajero">Cajero</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="passwordUsuario">Contraseña:</label>
                    <input type="password" id="passwordUsuario" name="password">
                    <small id="passwordHelp">Dejar en blanco para mantener la contraseña actual (solo en edición)</small>
                </div>
                
                <div class="form-group">
                    <label for="activoUsuario">Estado:</label>
                    <select id="activoUsuario" name="activo">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                
                <div style="margin-top: 20px; text-align: right;">
                    <button type="button" class="btn btn-secondary" onclick="cerrarModalUsuario()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentTab = 'dashboard';

        document.addEventListener('DOMContentLoaded', function() {
            cargarDashboard();
            
            // Establecer fechas por defecto para reportes
            const hoy = new Date();
            const hace30dias = new Date();
            hace30dias.setDate(hoy.getDate() - 30);
            
            document.getElementById('fechaInicio').valueAsDate = hace30dias;
            document.getElementById('fechaFin').valueAsDate = hoy;
        });

        function cambiarTab(tab) {
            currentTab = tab;
            
            // Ocultar todas las secciones
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('hidden');
            });
            
            // Mostrar la sección activa
            document.getElementById(tab).classList.remove('hidden');
            
            // Actualizar botones de tab
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');
            
            // Cargar contenido según el tab
            switch(tab) {
                case 'dashboard':
                    cargarDashboard();
                    break;
                case 'usuarios':
                    cargarUsuarios();
                    break;
                case 'tickets':
                    cargarTodosTickets();
                    break;
                case 'reportes':
                    // Los reportes se cargan manualmente
                    break;
            }
        }

        function cargarDashboard() {
            fetch('api/admin_dashboard.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('totalUsuarios').textContent = data.stats.total_usuarios;
                        document.getElementById('totalTickets').textContent = data.stats.total_tickets;
                        document.getElementById('ticketsPendientes').textContent = data.stats.tickets_pendientes;
                        document.getElementById('ticketsPagados').textContent = data.stats.tickets_pagados;
                        
                        // Cargar actividad reciente
                        const tbody = document.getElementById('actividadReciente');
                        tbody.innerHTML = '';
                        
                        data.actividad.forEach(usuario => {
                            const row = tbody.insertRow();
                            row.innerHTML = `
                                <td>${usuario.nombre}</td>
                                <td>${usuario.ultimo_acceso || 'Nunca'}</td>
                                <td>${usuario.tickets_hoy}</td>
                                <td><span class="status-badge status-${usuario.activo ? 'activo' : 'inactivo'}">${usuario.activo ? 'Activo' : 'Inactivo'}</span></td>
                            `;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function cargarUsuarios() {
            fetch('api/admin_usuarios.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('usuariosTableBody');
                        tbody.innerHTML = '';
                        
                        data.usuarios.forEach(usuario => {
                            const row = tbody.insertRow();
                            row.innerHTML = `
                                <td>${usuario.usuario}</td>
                                <td>${usuario.nombre}</td>
                                <td><span class="role-badge role-${usuario.rol}">${usuario.rol.toUpperCase()}</span></td>
                                <td><span class="status-badge status-${usuario.activo ? 'activo' : 'inactivo'}">${usuario.activo ? 'Activo' : 'Inactivo'}</span></td>
                                <td>${new Date(usuario.fecha_creacion).toLocaleDateString()}</td>
                                <td>${usuario.ultimo_acceso ? new Date(usuario.ultimo_acceso).toLocaleString() : 'Nunca'}</td>
                                <td>
                                    <button class="btn btn-primary btn-small" onclick="editarUsuario(${usuario.id})">Editar</button>
                                    ${usuario.activo ? 
                                        `<button class="btn btn-warning btn-small" onclick="desactivarUsuario(${usuario.id})">Desactivar</button>` :
                                        `<button class="btn btn-success btn-small" onclick="activarUsuario(${usuario.id})">Activar</button>`
                                    }
                                    ${usuario.rol !== 'admin' ? 
                                        `<button class="btn btn-danger btn-small" onclick="eliminarUsuario(${usuario.id})">Eliminar</button>` : ''
                                    }
                                </td>
                            `;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function cargarTodosTickets() {
            fetch('api/admin_tickets.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarTickets(data.tickets);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function buscarTickets() {
            const busqueda = document.getElementById('searchTicket').value;
            fetch(`api/admin_tickets.php?busqueda=${encodeURIComponent(busqueda)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarTickets(data.tickets);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function mostrarTickets(tickets) {
            const tbody = document.getElementById('ticketsTableBody');
            tbody.innerHTML = '';
            
            tickets.forEach(ticket => {
                const row = tbody.insertRow();
                row.innerHTML = `
                    <td>${ticket.fecha_emision}</td>
                    <td>${ticket.paciente}</td>
                    <td>${ticket.cedula}</td>
                    <td>$${parseFloat(ticket.monto_usd).toFixed(2)}</td>
                    <td><span class="status-badge status-${ticket.estado === 'pendiente' ? 'inactivo' : 'activo'}">${ticket.estado.toUpperCase()}</span></td>
                    <td>${ticket.nombre_creador}</td>
                    <td>
                        <button class="btn btn-primary btn-small" onclick="verDetalleTicket(${ticket.id})">Ver</button>
                        ${ticket.estado === 'pendiente' ? 
                            `<button class="btn btn-danger btn-small" onclick="eliminarTicket(${ticket.id})">Eliminar</button>` : ''
                        }
                    </td>
                `;
            });
        }

        function mostrarModalUsuario(esEdicion = false) {
            document.getElementById('modalTitle').textContent = esEdicion ? 'Editar Usuario' : 'Crear Nuevo Usuario';
            document.getElementById('passwordHelp').style.display = esEdicion ? 'block' : 'none';
            document.getElementById('usuarioModal').style.display = 'block';
            
            if (!esEdicion) {
                document.getElementById('usuarioForm').reset();
                document.getElementById('usuarioId').value = '';
            }
        }

        function cerrarModalUsuario() {
            document.getElementById('usuarioModal').style.display = 'none';
        }

        function editarUsuario(id) {
            fetch(`api/admin_usuario.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const usuario = data.usuario;
                        document.getElementById('usuarioId').value = usuario.id;
                        document.getElementById('usuarioNombre').value = usuario.usuario;
                        document.getElementById('nombreCompleto').value = usuario.nombre;
                        document.getElementById('rolUsuario').value = usuario.rol;
                        document.getElementById('activoUsuario').value = usuario.activo ? '1' : '0';
                        document.getElementById('passwordUsuario').value = '';
                        
                        mostrarModalUsuario(true);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function desactivarUsuario(id) {
            if (confirm('¿Está seguro de que desea desactivar este usuario?')) {
                cambiarEstadoUsuario(id, 0);
            }
        }

        function activarUsuario(id) {
            cambiarEstadoUsuario(id, 1);
        }

        function cambiarEstadoUsuario(id, estado) {
            fetch('api/admin_cambiar_estado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id, activo: estado })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Estado del usuario actualizado exitosamente', 'success');
                    cargarUsuarios();
                } else {
                    mostrarAlerta('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al cambiar estado del usuario', 'error');
            });
        }

        function eliminarUsuario(id) {
            if (confirm('¿Está seguro de que desea eliminar este usuario? Esta acción no se puede deshacer.')) {
                fetch('api/admin_eliminar_usuario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('Usuario eliminado exitosamente', 'success');
                        cargarUsuarios();
                    } else {
                        mostrarAlerta('Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al eliminar usuario', 'error');
                });
            }
        }

        // Crear/Editar usuario
        document.getElementById('usuarioForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const usuarioId = document.getElementById('usuarioId').value;
            
            const url = usuarioId ? 'api/admin_editar_usuario.php' : 'api/admin_crear_usuario.php';
            if (usuarioId) {
                formData.append('id', usuarioId);
            }
            
            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Usuario guardado exitosamente', 'success');
                    cerrarModalUsuario();
                    cargarUsuarios();
                } else {
                    mostrarAlerta('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al guardar usuario', 'error');
            });
        });

        function generarReporte() {
            const fechaInicio = document.getElementById('fechaInicio').value;
            const fechaFin = document.getElementById('fechaFin').value;
            
            if (!fechaInicio || !fechaFin) {
                mostrarAlerta('Por favor seleccione las fechas de inicio y fin', 'error');
                return;
            }
            
            fetch(`api/admin_reportes.php?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarReporte(data.reporte);
                    } else {
                        mostrarAlerta('Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al generar reporte', 'error');
                });
        }

        function mostrarReporte(reporte) {
            const container = document.getElementById('reporteResultados');
            
            container.innerHTML = `
                <h3>Reporte del ${document.getElementById('fechaInicio').value} al ${document.getElementById('fechaFin').value}</h3>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">${reporte.total_tickets}</div>
                        <div class="stat-label">Tickets Totales</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">$${parseFloat(reporte.total_usd).toFixed(2)}</div>
                        <div class="stat-label">Total en USD</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">Bs ${parseFloat(reporte.total_bs).toLocaleString()}</div>
                        <div class="stat-label">Total en Bolívares</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">${reporte.tickets_pagados}</div>
                        <div class="stat-label">Tickets Pagados</div>
                    </div>
                </div>
                
                <h4>Por Usuario:</h4>
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Tickets Creados</th>
                            <th>Total USD</th>
                            <th>Total Bs</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${reporte.por_usuario.map(usuario => `
                            <tr>
                                <td>${usuario.nombre}</td>
                                <td>${usuario.total_tickets}</td>
                                <td>$${parseFloat(usuario.total_usd).toFixed(2)}</td>
                                <td>Bs ${parseFloat(usuario.total_bs).toLocaleString()}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        function exportarReporte() {
            const fechaInicio = document.getElementById('fechaInicio').value;
            const fechaFin = document.getElementById('fechaFin').value;
            
            if (!fechaInicio || !fechaFin) {
                mostrarAlerta('Por favor seleccione las fechas de inicio y fin', 'error');
                return;
            }
            
            window.open(`api/admin_exportar.php?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`, '_blank');
        }

        function mostrarAlerta(mensaje, tipo) {
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.innerHTML = `<div class="alert alert-${tipo}">${mensaje}</div>`;
            
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        function logout() {
            fetch('logout.php')
                .then(() => {
                    window.location.href = 'login.php';
                });
        }

        // Cerrar modal al hacer clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('usuarioModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>