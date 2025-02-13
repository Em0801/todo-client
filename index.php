<?php
require_once 'apiConection.class.php';

try {
    $api = new ApiConnection();
    $response = $api->getAllTasks();
    $tasks = $response['data'] ?? []; // Extraemos el array de tareas de data
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO-DO List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Lista de Tareas</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para nueva tarea -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Nueva Tarea</h5>
                <form id="newTaskForm">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Tarea</button>
                </form>
            </div>
        </div>

        <!-- Lista de tareas -->
        <div class="table-responsive">
            <?php if (!empty($tasks)): ?>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                            <th>Última Actualización</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($task['id']); ?></td>
                                <td><?php echo htmlspecialchars($task['title']); ?></td>
                                <td><?php echo htmlspecialchars($task['description']); ?></td>
                                <td>
                                    <span class="badge <?php echo $task['status'] === 'completed' ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo htmlspecialchars($task['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($task['created_at'])); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($task['updated_at'])); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn <?php echo $task['status'] === 'completed' ? 'btn-warning' : 'btn-success'; ?> toggle-status" 
                                                data-id="<?php echo $task['id']; ?>"
                                                data-status="<?php echo $task['status']; ?>"
                                                title="<?php echo $task['status'] === 'completed' ? 'Marcar Pendiente' : 'Marcar Completada'; ?>">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button class="btn btn-danger delete-task" 
                                                data-id="<?php echo $task['id']; ?>"
                                                title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">
                    No hay tareas disponibles.
                </div>
            <?php endif; ?>
        </div>

        <!-- Agregar los íconos de Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Crear nueva tarea
            $('#newTaskForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/API-TO-DO/tasks',
                    method: 'POST',
                    headers: {
                        'Authorization': 'testapikey1234567890'
                    },
                    data: JSON.stringify({
                        title: $('#title').val(),
                        description: $('#description').val(),
                        status: 'pending'
                    }),
                    contentType: 'application/json',
                    success: function() {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error al crear la tarea: ' + xhr.responseText);
                    }
                });
            });

            // Cambiar estado de tarea
            $('.toggle-status').click(function() {
                const id = $(this).data('id');
                const currentStatus = $(this).data('status');
                const newStatus = currentStatus === 'pending' ? 'completed' : 'pending';

                $.ajax({
                    url: `/API-TO-DO/tasks/${id}`,
                    method: 'PUT',
                    headers: {
                        'Authorization': 'testapikey1234567890'
                    },
                    data: JSON.stringify({ status: newStatus }),
                    contentType: 'application/json',
                    success: function() {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error al actualizar la tarea: ' + xhr.responseText);
                    }
                });
            });

            // Eliminar tarea
            $('.delete-task').click(function() {
                if (confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
                    const id = $(this).data('id');
                    $.ajax({
                        url: `/API-TO-DO/tasks/${id}`,
                        method: 'DELETE',
                        headers: {
                            'Authorization': 'testapikey1234567890'
                        },
                        success: function() {
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('Error al eliminar la tarea: ' + xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
