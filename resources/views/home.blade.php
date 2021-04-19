@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @auth
                        <h1>Bem vindo A Simple Blog</h1>
                        <p><a href="{{ route('articles') }}">Acessar Artigos!</a></p>
                    @endauth

                    @guest
                        <h1>Bem vindo A Simple Blog!</h1>
                        <p>Este é um Blog Privado, Para começar a usar</p>
                        <a href="{{ route('register') }}">cadastre-se</a>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
