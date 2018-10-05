@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Home</div>
                {{-- <v-form method="POST" action="{{ route('generate_pdf') }}">
                    @csrf
                    <v-btn type="submit" flat color="primary">Submit</v-btn>
                </v-form> --}}

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                            <a href="/login" class="btn btn-primary ">Login</a>
                        </div>
                    @endif
                    Welcome {{ Auth::user()->name }} to our app
                        <br>
                <a href="/courier" class="btn btn-primary">Go to the app</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
