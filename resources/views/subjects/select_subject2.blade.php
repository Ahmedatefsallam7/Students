<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Subjects</title>
</head>

<body>
    <label for="se">Choose Subject</label>
    @if (!isset($close))
        <form action="{{ route('start-timer') }}" method="post">
            @csrf
            <select name="sub_id" id="se">
                @foreach ($subjects as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->sub_name }}
                    </option>
                @endforeach
            </select>
            <input type="submit" value="Start Timer">
        </form>
    @else
        <form action="{{ route('start-timer', $close = 1) }}" method="post">
            @csrf
            <select name="sub_id" id="se">
                @foreach ($subjects as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->sub_name }}
                    </option>
                @endforeach
            </select>
            <input type="submit" value="End Timer">
        </form>
    @endif


</body>

</html>
