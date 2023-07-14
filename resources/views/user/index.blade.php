<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>User list</title>

    </head>
    <body>
        <div>
        	<table class="table table-bordered m-0 text-center table-hover table-striped table-scroll">
                    <thead>
                    <tr class="bg-secondary">
                        <th><span>ID</span></th>
                        <th><span>Email</span></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </body>
</html>
