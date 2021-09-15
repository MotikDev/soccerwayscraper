@extends('Predict')

    @section('title')
    <h1 class="text-primary"> <strong>No Both Team to Score OR No GG</strong></h1>
    <br>
    @endsection
    <br>
    
       
    @section('content')
    <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Match URL</th>
                <th>Home Average Matches Played</th>
                <th>Away Average Matches Played</th>
                <th>Home AG-scored</th>
                <th>Home AG-conceded</th>
                <th>Away AG-scored</th>
                <th>Away AG-conceded</th>
            </tr>
        </thead>
        <tbody>
                <div class="hidden">
                        {{ $count = 0 }}
                </div>
            @foreach ($todayNoBTTS as $matches)
                <div class="hidden">
                    {{ $count = $count + 1 }}
                </div>
                <tr>
                    <td>{{ $count }}</td>
                    <td><a href="{{ $matches->URL }}">Link</a> </td>
                    <td>{{ $matches->Home_MP }}</td>
                    <td>{{ $matches->Away_MP }}</td>
                    <td>{{ $matches->Home_AGS }}</td>
                    <td>{{ $matches->Home_AGC }}</td>
                    <td>{{ $matches->Away_AGS }}</td>
                    <td>{{ $matches->Away_AGC }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br><br><br><br>

    <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Match URL</th>
                <th>Home Average Matches Played</th>
                <th>Away Average Matches Played</th>
                <th>Home AG-scored</th>
                <th>Home AG-conceded</th>
                <th>Away AG-scored</th>
                <th>Away AG-conceded</th>
            </tr>
        </thead>
        <tbody>
                <div class="hidden">
                        {{ $count = 0 }}
                </div>
            @foreach ($todayNoBTTS2 as $matches)
                <div class="hidden">
                    {{ $count = $count + 1 }}
                </div>
                <tr>
                    <td>{{ $count }}</td>
                    <td><a href="{{ $matches->URL }}">Link</a> </td>
                    <td>{{ $matches->Home_MP }}</td>
                    <td>{{ $matches->Away_MP }}</td>
                    <td>{{ $matches->Home_AGS }}</td>
                    <td>{{ $matches->Home_AGC }}</td>
                    <td>{{ $matches->Away_AGS }}</td>
                    <td>{{ $matches->Away_AGC }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endsection