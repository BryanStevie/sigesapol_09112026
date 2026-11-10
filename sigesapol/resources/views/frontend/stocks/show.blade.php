@extends('frontend.layouts.master')

@section('content')
<div class="container">

    <h1>Stock</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID.</th> <th>{{ trans('stocks.monto_inicial') }}</th><th>{{ trans('stocks.monto_actual') }}</th><th>{{ trans('stocks.monto_consumido') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $stock->id }}</td> <td> {{ $stock->monto_inicial }} </td><td> {{ $stock->monto_actual }} </td><td> {{ $stock->monto_consumido }} </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
@include('frontend.includes.footer')
@endsection