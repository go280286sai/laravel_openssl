<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <a href="{{env('APP_URL')}}/resource/create"><div class="btn btn-primary">Add resource</div></a>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Url</th>
            <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody>
        @foreach($resource as $item)
        <tr>
            <th scope="row">{{$item['id']}}</th>
            <td><a href="{{env('APP_URL')}}resource/{{$item['id']}}/edit">{{$item['name']}}</a></td>
            <td>{{$item['url']}}</td>
            <td>
                <form action="{{env('APP_URL')}}/resource/{{$item['id']}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
               </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>

