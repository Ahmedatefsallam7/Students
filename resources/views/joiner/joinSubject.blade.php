<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Join Subject</title>
</head>

<body>
    <form action="{{ route('storeJoiner') }}" method="post">
        @csrf
        <input type="hidden" name='sub_id' value="{{ $sub->id }}">
        <input type="text" name='sub_code' value="{{ $sub->sub_code }}">
        <br><br>
        <button type="submit">Join</button>
    </form>

</body>

</html>
