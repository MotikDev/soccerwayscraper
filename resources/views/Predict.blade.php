<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
</head>
<body>
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center text-danger"> <strong> My Predictions </strong> </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2" style="background:black; border-radius:5%; height:200%; text-align:center">
            <br>
            <a href="{{ action('Scrap@win') }}" class="btn btn-success btn-lg">Win/DNB</a><br><br>
            <a href="{{ action('Scrap@over') }}" class="btn btn-success btn-lg">Over 2.5</a><br><br>
            <a href="{{ action('Scrap@under') }}" class="btn btn-success btn-lg">Under 2.5</a><br><br>
            <a href="{{ action('Scrap@btts') }}" class="btn btn-success btn-lg">BTTS/GG</a><br><br>
            <a href="{{ action('Scrap@noBTTS') }}" class="btn btn-success btn-md">No BTTS/No GG</a><br><br>
            <a href="{{ route('all') }}" class="btn btn-success btn-lg">More</a><br><br>
            <a href="{{ action('Scrap@create') }}" class="btn btn-success btn-lg">Create</a><br><br>
        </div>
        <div class="col-md-10">
            @yield('title')
            @yield('content')
        </div>
    </div>

</body>
</html>