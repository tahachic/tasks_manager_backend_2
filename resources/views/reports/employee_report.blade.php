<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de l'Employé</title>
    <style>
        body{
            font-family: Cairo ,
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
    <style>
        @font-face {
            font-family: 'Cairo';
            src: url('{{ storage_path("fonts/Cairo-Regular.ttf") }}') format('truetype');
        }
        body {
            font-family: 'Cairo', sans-serif;
            direction: ltr; /* Ajoute la direction de l'écriture pour l'arabe */
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2 >Rapport de l'Employé - {{ $employee->name }}</h2>
    <p>ID: {{ $employee->id }}</p>
    <p>Département: {{ $employee->department->name ?? 'Non défini' }}</p>

    <h3>Tâches du Jour</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Statut</th>
            <th>Priorité</th>
            <th>Créé à</th>
        </tr>
        @foreach($dailyTasks as $task)
        <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $task->title }}</td>
            <td>{{ $task->status }}</td>
            <td>{{ $task->priority }}</td>
            <td>{{ $task->created_at }}</td>
        </tr>
        @endforeach
    </table>

    <h3>Toutes les Tâches</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Statut</th>
            <th>Priorité</th>
            <th>Créé à</th>
        </tr>
        @foreach($allTasks as $task)
        <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $task->title }} </td>
            <td>{{ $task->status }}</td>
            <td>{{ $task->priority }}</td>
            <td>{{ $task->created_at }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
