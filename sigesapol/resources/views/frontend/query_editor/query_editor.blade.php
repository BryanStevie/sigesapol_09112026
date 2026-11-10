@extends('frontend.layouts.master')
@section('renderjs')
<style>
  .query {
    font-size: 12pt;
    font-family: monospace;
    line-height: 15pt;
    resize: none;
  }
</style>

<script type="text/javascript" language="javascript"
  src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript"
  src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript"
  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript"
  src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript"
  src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script>
  $(document).ready(function() {
  $(".data-table").DataTable({
    dom: 'Bfrtip',
    buttons: [
      'excel', 'pdf', 'csv'
    ]
  });
});
</script>

@endsection
@section('content')
<div class="container">
  <div class="col-md-10 well col-md-offset-1" style="padding-top:0px">
    <div class="row margin" style=" font-size: 13px">

      <form action="{{ route('procesa_query') }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
          <br>
          <label for="txtquery">Query</label>
          <textarea class="form-control rounded-0 query" id="txtquery" name="txtquery"
            rows="10">{{isset($query_string) ? $query_string : ''}}{{{ Input::old('txtquery') }}}</textarea>
        </div>
        <input id="" name="" type="submit" value="Ejecutar Query" class="btn btn-success pull-right">
      </form>
      <br><br><br>
    </div>
    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(isset($result_query))
    <div class="row">
      @if (is_string($result_query))
      <label>{{$result_query}}</label><br>
      @else
      <label>Registros afectados: {{count($result_query)}}</label><br>
      <div class="table-responsive" style="background:#fff">
        <table class="table table-bordered table-sm data-table">
          <thead class="thead-dark">
            <tr>
              @foreach ($result_keys as $key)
              <th>{{$key}}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach ($result_query as $values)
            <tr>
              @foreach ($result_keys as $key)
              <td>{{$values->$key}}</td>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
        @endif
      </div>
    </div>
    @endif

  </div>
</div>
@include('frontend.includes.footer')
@endsection