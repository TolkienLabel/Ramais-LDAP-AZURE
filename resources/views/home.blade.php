@extends('layouts.app')

@section('content')

<!-- OBJETO VUE.js recebendo lista de usuários via JSON direto pelo Blade -->
<!-- resources/js/components/RamaisComponent.vue -->
<ramais-component users="{{json_encode($userslist)}}"></ramais-component>

@endsection
