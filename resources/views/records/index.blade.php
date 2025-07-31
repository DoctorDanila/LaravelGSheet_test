<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mb-4">Records</h1>

    <form method="POST" action="{{ route('generate') }}">
        @csrf
        <button class="btn btn-primary mb-2">Сгенерировать 1000 записей</button>
    </form>

    <form method="POST" action="{{ route('clear') }}">
        @csrf
        <button class="btn btn-danger mb-4">Очистить таблицу</button>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th><th>Text</th><th>Status</th><th>Created</th><th>Updated</th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $record)
            <tr>
                <td>{{ $record->id }}</td>
                <td>{{ $record->text }}</td>
                <td>{{ $record->status->value }}</td>
                <td>{{ $record->created_at }}</td>
                <td>{{ $record->updated_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
