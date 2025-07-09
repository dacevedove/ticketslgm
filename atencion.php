<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atención Integral - Sistema de Tickets</title>
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

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-section, .tickets-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-section h2, .tickets-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
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

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        textarea {
            resize: vertical;
            min-height: 60px;
        }

        .tickets-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .tickets-table th,
        .tickets-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .tickets-table th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
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
            max-width: 600px;
            max-height: 80vh;
            overflow-y: auto;
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

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Atención Integral</h1>
        <div class="user-info">
            <span>Bienvenido, <strong id="userName">Usuario</strong></span>
            <button class="btn btn-secondary" onclick="logout()">Cerrar Sesión</button>
        </div>
    </div>

    <div id="alertContainer"></div>

    <div class="main-content">
        <div class="form-section">
            <h2>Crear Nuevo Ticket</h2>
            <form id="ticketForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tasaDia">Tasa del día:</label>
                        <input type="number" id="tasaDia" name="tasaDia" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="fechaEmision">Fecha de emisión:</label>
                        <input type="date" id="fechaEmision" name="fechaEmision" required>
                    </div>
                    <div class="form-group">
                        <label for="paciente">Paciente:</label>
                        <input type="text" id="paciente" name="paciente" required>
                    </div>
                    <div class="form-group">
                        <label for="cedula">Cédula de identidad:</label>
                        <input type="text" id="cedula" name="cedula" placeholder="12345678" required>
                    </div>
                    <div class="form-group">
                        <label for="historia">Historia:</label>
                        <input type="text" id="historia" name="historia">
                    </div>
                    <div class="form-group">
                        <label for="numeroHistoria">Número:</label>
                        <input type="number" id="numeroHistoria" name="numeroHistoria">
                    </div>
                    <div class="form-group">
                        <label for="montoBs">Monto Bs:</label>
                        <input type="number" id="montoBs" name="montoBs" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="montoUsd">Monto $:</label>
                        <input type="number" id="montoUsd" name="montoUsd" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="factura">Factura:</label>
                        <input type="text" id="factura" name="factura">
                    </div>
                    <div class="form-group full-width">
                        <label for="procedimiento">Procedimiento:</label>
                        <textarea id="procedimiento" name="procedimiento" rows="3"></textarea>
                    </div>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">Crear Ticket</button>
                    <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">Limpiar</button>
                </div>
            </form>
        </div>

        <div class="tickets-section">
            <h2>Mis Tickets</h2>
            <div style="margin-bottom: 15px;">
                <button class="btn btn-secondary" onclick="cargarTickets()">Actualizar</button>
            </div>
            <div style="max-height: 400px; overflow-y: auto;">
                <table class="tickets-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Paciente</th>
                            <th>Cédula</th>
                            <th>Monto $</th>
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
    </div>

    <!-- Modal para editar ticket -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h2>Editar Ticket</h2>
            <form id="editForm">
                <input type="hidden" id="editTicketId">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="editTasaDia">Tasa del día:</label>
                        <input type="number" id="editTasaDia" name="tasaDia" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="editFechaEmision">Fecha de emisión:</label>
                        <input type="date" id="editFechaEmision" name="fechaEmision" required>
                    </div>
                    <div class="form-group">
                        <label for="editPaciente">Paciente:</label>
                        <input type="text" id="editPaciente" name="paciente" required>
                    </div>
                    <div class="form-group">
                        <label for="editCedula">Cédula de identidad:</label>
                        <input type="text" id="editCedula" name="cedula" required>
                    </div>
                    <div class="form-group">
                        <label for="editHistoria">Historia:</label>
                        <input type="text" id="editHistoria" name="historia">
                    </div>
                    <div class="form-group">
                        <label for="editNumeroHistoria">Número:</label>
                        <input type="number" id="editNumeroHistoria" name="numeroHistoria">
                    </div>
                    <div class="form-group">
                        <label for="editMontoBs">Monto Bs:</label>
                        <input type="number" id="editMontoBs" name="montoBs" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="editMontoUsd">Monto $:</label>
                        <input type="number" id="editMontoUsd" name="montoUsd" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="editFactura">Factura:</label>
                        <input type="text" id="editFactura" name="factura">
                    </div>
                    <div class="form-group full-width">
                        <label for="editProcedimiento">Procedimiento:</label>
                        <textarea id="editProcedimiento" name="procedimiento" rows="3"></textarea>
                    </div>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn btn-success">Actualizar Ticket</button>
                    <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Cargar información del usuario al iniciar
        document.addEventListener('DOMContentLoaded', function() {
            // Establecer fecha actual
            document.getElementById('fechaEmision').valueAsDate = new Date();
            
            // Cargar tickets
            cargarTickets();
            
            // Calcular USD basado en tasa y monto Bs
            document.getElementById('tasaDia').addEventListener('input', calcularUSD);
            document.getElementById('montoBs').addEventListener('input', calcularUSD);
        });

        function calcularUSD() {
            const tasa = parseFloat(document.getElementById('tasaDia').value) || 0;
            const montoBs = parseFloat(document.getElementById('montoBs').value) || 0;
            
            if (tasa > 0 && montoBs > 0) {
                const montoUsd = (montoBs / tasa).toFixed(2);
                document.getElementById('montoUsd').value = montoUsd;
            }
        }

        // Crear ticket
        document.getElementById('ticketForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('api/crear_ticket.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Ticket creado exitosamente', 'success');
                    limpiarFormulario();
                    cargarTickets();
                } else {
                    mostrarAlerta('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al crear el ticket', 'error');
            });
        });

        // Editar ticket
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('id', document.getElementById('editTicketId').value);
            
            fetch('api/editar_ticket.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Ticket actualizado exitosamente', 'success');
                    cerrarModal();
                    cargarTickets();
                } else {
                    mostrarAlerta('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlerta('Error al actualizar el ticket', 'error');
            });
        });

        function cargarTickets() {
            fetch('api/obtener_tickets.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('ticketsTableBody');
                        tbody.innerHTML = '';
                        
                        data.tickets.forEach(ticket => {
                            const row = tbody.insertRow();
                            row.innerHTML = `
                                <td>${ticket.fecha_emision}</td>
                                <td>${ticket.paciente}</td>
                                <td>${ticket.cedula}</td>
                                <td>$${parseFloat(ticket.monto_usd).toFixed(2)}</td>
                                <td><span class="status-badge status-${ticket.estado}">${ticket.estado.toUpperCase()}</span></td>
                                <td>
                                    ${ticket.estado === 'pendiente' ? `
                                        <button class="btn btn-primary" onclick="editarTicket(${ticket.id})" style="padding: 5px 10px; margin-right: 5px;">Editar</button>
                                        <button class="btn btn-danger" onclick="eliminarTicket(${ticket.id})" style="padding: 5px 10px;">Eliminar</button>
                                    ` : 'Pagado'}
                                </td>
                            `;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function editarTicket(id) {
            fetch(`api/obtener_ticket.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const ticket = data.ticket;
                        document.getElementById('editTicketId').value = ticket.id;
                        document.getElementById('editTasaDia').value = ticket.tasa_dia;
                        document.getElementById('editFechaEmision').value = ticket.fecha_emision;
                        document.getElementById('editPaciente').value = ticket.paciente;
                        document.getElementById('editCedula').value = ticket.cedula;
                        document.getElementById('editHistoria').value = ticket.historia || '';
                        document.getElementById('editNumeroHistoria').value = ticket.numero_historia || '';
                        document.getElementById('editMontoBs').value = ticket.monto_bs;
                        document.getElementById('editMontoUsd').value = ticket.monto_usd;
                        document.getElementById('editFactura').value = ticket.factura || '';
                        document.getElementById('editProcedimiento').value = ticket.procedimiento || '';
                        
                        document.getElementById('editModal').style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function eliminarTicket(id) {
            if (confirm('¿Está seguro de que desea eliminar este ticket?')) {
                fetch('api/eliminar_ticket.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        mostrarAlerta('Ticket eliminado exitosamente', 'success');
                        cargarTickets();
                    } else {
                        mostrarAlerta('Error: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarAlerta('Error al eliminar el ticket', 'error');
                });
            }
        }

        function cerrarModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function limpiarFormulario() {
            document.getElementById('ticketForm').reset();
            document.getElementById('fechaEmision').valueAsDate = new Date();
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
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>