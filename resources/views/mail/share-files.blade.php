<h1>Hello {{ $user->name }}</h1>

<h3>User <b>{{ $author->name }}</b> shared the following files to you.</h3>


@foreach ($files as $file)
    <p>{{ $file->is_folder ? 'Folder' : 'File' }} - {{ $file->name}}</p>
@endforeach
