<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Select Subject</title>
</head>

<body>
    <label for="se">Choose Subject</label>

    <form action="{{ route('getAttendances') }}" method="post">
        @csrf
        <select name="sub_id" id="se">
            @foreach ($subjects as $sub)
                <option value="{{ $sub->id }}">
                    {{ $sub->sub_name }}
                </option>
            @endforeach
        </select>
        <input type="submit" value="Select Subject">
    </form>

</body>

</html>
