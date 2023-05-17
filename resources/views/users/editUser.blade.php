<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit User</title>
</head>

<body>

    <form action="{{ route('update', $user->id) }}" method="post">
        @method('put')
        @csrf
        <label for="name">UserName : </label>
        <input type="text" name="name" id="name" value="{{ $user->name }}">

        <br><br>

        <label for="email">Email : </label>
        <input type="text" name="email" id="email" value="{{ $user->email }}">

        {{-- <label for="pass">Password : </label>
        <input type="text" name="password" id="pass" value="{{ decrypt($user->password) }}"> --}}

        <br><br>

        <input type="submit" value="Edit User">

    </form>

</body>

</html>
