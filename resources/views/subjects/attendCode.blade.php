<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attend code</title>
</head>

<body>

    <form action="{{ route('attend') }}" method="post">
        @csrf
        <input type="text" value="{{ $subject->attend_code }}">
        <br>
        <input type="text" name="check" placeholder="Enter Attend Code" autofocus required>
        <input type="submit" value="Attend">
    </form>

</body>

</html>
