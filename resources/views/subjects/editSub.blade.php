<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Subject</title>
</head>

<body>

    <form action="{{ route('updateSub', $subject->id) }}" method="post">
        @csrf
        @method('put')
        <label for="name">SubjectName : </label>
        <input type="text" name="sub_name" id="name" value="{{ $subject->sub_name }}">
        <br>
        <br>
        <input type="submit" value="Edit Subject">

    </form>

</body>

</html>
