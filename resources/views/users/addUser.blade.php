<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add New User</title>
</head>

<body>

    <form action="{{ route('store') }}" method="post">
        @csrf
        <label for="name">UserName : </label>
        <input type="text" name="name" id="name" placeholder="Enter The Name">

        <br><br>

        <label for="email">Email : </label>
        <input type="text" name="email" id="email" placeholder="Enter Email">

        <br><br>

        <label for="pass">Password : </label>
        <input type="text" name="password" id="pass" placeholder="Enter Password">

        <br><br>

        <input type="submit" value="Add User">

    </form>

</body>

</html>
