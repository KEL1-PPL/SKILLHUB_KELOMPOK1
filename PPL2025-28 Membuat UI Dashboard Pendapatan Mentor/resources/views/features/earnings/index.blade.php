<!DOCTYPE html>
<html>
<head>
    <title>Mentor Earnings</title>
</head>
<body>
    <h1>Mentor Earnings Report</h1>
    <ul>
        @foreach ($earnings as $earning)
            <li>ID: {{ $earning['id'] }} - Rp{{ number_format($earning['amount']) }} - Note: {{ $earning['note'] }}</li>
        @endforeach
    </ul>
</body>
</html>
