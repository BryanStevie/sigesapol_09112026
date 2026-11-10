@extends('frontend.layouts.master')
@section('renderjs')
<style>
  
</style>
<script>
$(document).ready(function () {
        $('#example').DataTable();
});
</script>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="panel-group">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title"> Almacen Central</h4>
                </div>
                <div class="panel-body">
                    <table id="example" class="table margenes" style="font-size: 11px !important;" >
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Código</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Nro</td>
                                <td>Código</td>
                            </tr>
                        </tbody>
                    </table>
                </div> 
            </div>                       
        </div>
    </div>
</div>
@include('frontend.includes.footer')
@endsection
