<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create New Subject</title>
</head>

<body>
    <form action="{{ route('storeSubject') }}" method="post">
        @csrf
        <label for="sub">Subject Name :</label>
        <input type="text" name=sub_name placeholder="Enter Subject Name" required>
        <button type="submit">Create</button>
    </form>
</body>

</html>
