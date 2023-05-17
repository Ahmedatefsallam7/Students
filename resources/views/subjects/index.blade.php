<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Subjects</title>
</head>

<body>
    <label for="se">Subject Code</label>

    <form action="{{ route('storeJoiner') }}" method="post">
        @csrf
        <input type="text" name='subject_code' required autofocus placeholder="Enter Subject Code To Join">
        <input type="submit" value="join">
    </form>

</body>

</html>
