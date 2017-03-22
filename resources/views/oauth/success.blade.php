@extends('layout')

@section('title', 'Wellcome to the Auth Service')

@section('content')

    <script type="text/javascript">
        window.opener.location.href = '{{ url('/profile') }}';
        self.close();
    </script>

@endsection