<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cajero - Sistema de Tickets</title>
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

        .search-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-form input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
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
            position: relative;
        }

        .tab.active {
            background: #667eea;
            color: white;
        }

        .tab:hover:not(.active) {
            background: #e2e8f0;
        }

        .tab-badge {
            position: absolute;
            top: 5px;
            right: 10px;
            background: #f56565;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tab.active .tab-badge {
            background: rgba(255,255,255,0.3);
        }

        .tickets-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .tickets-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .tickets-table th,
        .tickets-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .tickets-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            position: sticky;
            top: 0;
        }

        .tickets-table tbody tr:hover {
            background: #f8f9fa;
        }

        .btn {
            padding: 8px 16px;
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

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-pendiente {
            background: #fed7d7;
            color: #c53030;
        }

        .status-pagado {
            background: #c6f6d5;
            color: #22543d;
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

        .auto-refresh {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .auto-refresh input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }

        .refresh-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #48bb78;
            margin-left: 5px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .refresh-indicator.active {
            opacity: 1;
        }

        .table-container {
            max-height: 500px;
            overflow-y: auto;
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
            margin: 10% auto;
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

        .ticket-details {
            margin: 20px 0;
        }

        .ticket-details .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .ticket-details .detail-row:last-child {
            border-bottom: none;
        }

        .ticket-details .detail-label {
            font-weight: bold;
            color: #333;
        }

        .ticket-details .detail-value {
            color: #666;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .tickets-table {
                font-size: 12px;
            }
            
            .tickets-table th,
            .tickets-table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Cajero - Sistema de Tickets</h1>
        <div class="user-info">
            <span>Bienvenido, <strong id="userName">Usuario</strong></span>
            <button class="btn btn-secondary" onclick="logout()">Cerrar Sesión</button>
        </div>
    </div>

    <div class="search-section">
        <h3>Buscar Ticket</h3>
        <div class="search-form">
            <input type="text" id="searchCedula" placeholder="Ingrese número de cédula..." maxlength="8">
            <button class="btn btn-primary" onclick="buscarPorCedula()">Buscar</button>
            <button class="btn btn-secondary" onclick="limpiarBusqueda()">Limpiar</button>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="tabs">
        <button class="tab active" onclick="cambiarTab('pendientes')">
            Tickets Pendientes
            <span class="tab-badge" id="pendientesBadge">0</span>
        </button>
        <button class="tab" onclick="cambiarTab('pagados')">
            Tickets Pagados
            <span class="tab-badge" id="pagadosBadge">0</span>
        </button>
    </div>

    <div class="tickets-section">
        <div class="auto-refresh">
            <input type="checkbox" id="autoRefresh" checked>
            <label for="autoRefresh">Actualización automática (10 segundos)</label>
            <span class="refresh-indicator" id="refreshIndicator"></span>
        </div>
        
        <button class="btn btn-secondary" onclick="cargarTickets()">Actualizar Manualmente</button>
        
        <div class="table-container">
            <table class="tickets-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Paciente</th>
                        <th>Cédula</th>
                        <th>Monto Bs</th>
                        <th>Monto USD</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="ticketsTableBody">
                    <!-- Los tickets se cargarán aquí -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para ver detalles del ticket -->
    <div id="ticketModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h2>Detalles del Ticket</h2>
            <div class="ticket-details" id="ticketDetails">
                <!-- Los detalles se cargarán aquí -->
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <button class="btn btn-success" id="marcarPagadoBtn" onclick="marcarComoPagado()">Marcar como Pagado</button>
                <button class="btn btn-secondary" onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        let currentTab = 'pendientes';
        let autoRefreshInterval;
        let currentTicketId = null;

        document.addEventListener('DOMContentLoaded', function() {
            cargarTickets();
            configurarAutoRefresh();
            
            // Configurar búsqueda con Enter
            document.getElementById('searchCedula').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    buscarPorCedula();
                }
            });
        });

        function configurarAutoRefresh() {
            const checkbox = document.getElementById('autoRefresh');
            
            if (checkbox.checked) {
                autoRefreshInterval = setInterval(() => {
                    cargarTickets();
                    mostrarRefreshIndicator();
                }, 10000);
            }
            
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    autoRefreshInterval = setInterval(() => {
                        cargarTickets();
                        mostrarRefreshIndicator();
                    }, 10000);
                } else {
                    clearInterval(autoRefreshInterval);
                }
            });
        }

        function mostrarRefreshIndicator() {
            const indicator = document.getElementById('refreshIndicator');
            indicator.classList.add('active');
            setTimeout(() => {
                indicator.classList.remove('active');
            }, 500);
        }

        function cambiarTab(tab) {
            currentTab = tab;
            
            // Actualizar botones de tab
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            event.target.classList.add('active');
            
            cargarTickets();
        }

        function cargarTickets() {
            const cedula = document.getElementById('searchCedula').value.trim();
            let url = `api/obtener_tickets_cajero.php?estado=${currentTab}`;
            
            if (cedula) {
                url += `&cedula=${encodeURIComponent(cedula)}`;
            }
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarTickets(data.tickets);
                        actualizarBadges(data.counts);
                    } else {
                        mostrarAlerta('Error al cargar tickets: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar tickets', 'error');
                });
        }

        function mostrarTickets(tickets) {
            const tbody = document.getElementById('ticketsTableBody');
            tbody.innerHTML = '';
            
            if (tickets.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #666;">
                            No se encontraron tickets
                        </td>
                    </tr>
                `;
                return;
            }
            
            tickets.forEach(ticket => {
                const row = tbody.insertRow();
                row.innerHTML = `
                    <td>${ticket.fecha_emision}</td>
                    <td>${ticket.paciente}</td>
                    <td>${ticket.cedula}</td>
                    <td>Bs. ${parseFloat(ticket.monto_bs).toLocaleString('es-VE', {minimumFractionDigits: 2})}</td>
                    <td>$${parseFloat(ticket.monto_usd).toFixed(2)}</td>
                    <td><span class="status-badge status-${ticket.estado}">${ticket.estado.toUpperCase()}</span></td>
                    <td>
                        <button class="btn btn-primary btn-small" onclick="verDetalles(${ticket.id})">Ver Detalles</button>
                        ${ticket.estado === 'pendiente' ? 
                            `<button class="btn btn-success btn-small" onclick="marcarComoPagadoDirecto(${ticket.id})" style="margin-left: 5px;">Pagar</button>` : 
                            ''}
                    </td>
                `;
            });
        }

        function actualizarBadges(counts) {
            document.getElementById('pendientesBadge').textContent = counts.pendientes || 0;
            document.getElementById('pagadosBadge').textContent = counts.pagados || 0;
        }

        function buscarPorCedula() {
            const cedula = document.getElementById('searchCedula').value.trim();
            
            if (!cedula) {
                mostrarAlerta('Por favor ingrese un número de cédula', 'error');
                return;
            }
            
            if (!/^\d{7,8}$/.test(cedula)) {
                mostrarAlerta('El número de cédula debe tener 7 u 8 dígitos', 'error');
                return;
            }
            
            cargarTickets();
        }

        function limpiarBusqueda() {
            document.getElementById('searchCedula').value = '';
            cargarTickets();
        }

        function verDetalles(ticketId) {
            fetch(`api/obtener_ticket.php?id=${ticketId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarDetallesModal(data.ticket);
                    } else {
                        mostrarAlerta('Error al cargar detalles del ticket', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al cargar detalles del ticket', 'error');
                });
        }

        function mostrarDetallesModal(ticket) {
            currentTicketId = ticket.id;
            
            const detallesContainer = document.getElementById('ticketDetails');
            detallesContainer.innerHTML = `
                <div class="detail-row">
                    <span class="detail-label">Tasa del día:</span>
                    <span class="detail-value">${parseFloat(ticket.tasa_dia).toFixed(2)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fecha de emisión:</span>
                    <span class="detail-value">${ticket.fecha_emision}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Paciente:</span>
                    <span class="detail-value">${ticket.paciente}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cédula:</span>
                    <span class="detail-value">${ticket.cedula}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Historia:</span>
                    <span class="detail-value">${ticket.historia || 'No especificada'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Número de historia:</span>
                    <span class="detail-value">${ticket.numero_historia || 'No especificado'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Monto en Bs:</span>
                    <span class="detail-value">Bs. ${parseFloat(ticket.monto_bs).toLocaleString('es-VE', {minimumFractionDigits: 2})}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Monto en USD:</span>
                    <span class="detail-value">$${parseFloat(ticket.monto_usd).toFixed(2)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Factura:</span>
                    <span class="detail-value">${ticket.factura || 'No especificada'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Procedimiento:</span>
                    <span class="detail-value">${ticket.procedimiento || 'No especificado'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Estado:</span>
                    <span class="detail-value"><span class="status-badge status-${ticket.estado}">${ticket.estado.toUpperCase()}</span></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Creado por:</span>
                    <span class="detail-value">${ticket.nombre_creador}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fecha de creación:</span>
                    <span class="detail-value">${new Date(ticket.fecha_creacion).toLocaleString('es-VE')}</span>
                </div>
                ${ticket.estado === 'pagado' ? `
                    <div class="detail-row">
                        <span class="detail-label">Pagado por:</span>
                        <span class="detail-value">${ticket.nombre_cajero}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Fecha de pago:</span>
                        <span class="detail-value">${new Date(ticket.fecha_pago).toLocaleString('es-VE')}</span>
                    </div>
                ` : ''}
            `;
            
            const marcarPagadoBtn = document.getElementById('marcarPagadoBtn');
            marcarPagadoBtn.style.display = ticket.estado === 'pendiente' ? 'inline-block' : 'none';
            
            document.getElementById('ticketModal').style.display = 'block';
        }

        function marcarComoPagadoDirecto(ticketId) {
            if (confirm('¿Confirma que este ticket ha sido pagado?')) {
                procesarPago(ticketId);
            }
        }

        function marcarComoPagado() {
            if (currentTicketId && confirm('¿Confirma que este ticket ha sido pagado?')) {
                procesarPago(currentTicketId);
            }
        }

        function procesarPago(ticketId) {
            fetch('api/marcar_pagado.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: ticketId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Ticket marcado como pagado exitosamente', 'success');
                    cerrarModal();
                    cargarTickets();
                } else {
                    mostrarAlerta('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al procesar el pago', 'error');
            });
        }

        function cerrarModal() {
            document.getElementById('ticketModal').style.display = 'none';
            currentTicketId = null;
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
                    clearInterval(autoRefreshInterval);
                    window.location.href = 'login.php';
                });
        }

        // Cerrar modal al hacer clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('ticketModal');
            if (event.target === modal) {
                modal.style.display = 'none';
                currentTicketId = null;
            }
        }
    </script>
</body>
</html>