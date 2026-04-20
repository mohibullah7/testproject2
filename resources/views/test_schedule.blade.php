<!DOCTYPE html>
<html>
<head>
    <title>Schedule Test</title>
</head>
<body>

    <h1>Scheduler Test Page</h1>

    @if($schedule && $schedule->message)
        <p style="color: green; font-size: 20px;">
            {{ $schedule->message }}
        </p>
    @else
        <p style="color: red;">
            ❌ No message yet. Wait for scheduler...
        </p>
    @endif

</body>
</html>