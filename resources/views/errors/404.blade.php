@extends('layout.site', ['title' => 'Страница не найдена'])

@section('content')
    <h1>Страница не найдена</h1>
    <img src="{{ asset('img/404.jpg') }}" alt="" class="img-fluid">
    <p class="mt-3 mb-0">Запрошенная страница не найдена.</p>
@endsection
