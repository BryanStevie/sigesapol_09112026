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
$( "#guarda" ).click(function() {

    $('#frmguardar').submit();

});
</script>

@endsection
@section('content')
<div class="container">
  <div class="col-md-8 well col-md-offset-3" style="padding-top:0px"><br>
 <b> 
  USTED ESTA A PUNTO DE INGRESAR A UN SITIO DE USO EXCLUSIVO PARA PERSONAL AUTORIZADO TODO CAMBIO QUE REALICE, DEBE ESTAR DEBIDAMENTE JUSTIFICADO,<br>
  LOS CAMBIOS SERÁN AUDITADOS Y PASIBLES DE DENUNICIA ADMINISTRATIVA Y PENAL
</b>
  <div class="col-md-12" >
  <br>        
    <div class="col-md-4">
        <a href="/dashboard" class="btn btn-danger">NO ENTRAR</a>
    </div>
    <div class="col-md-4">
       
    </div>
    <div class="col-md-4">
        <a href="/editor_posgres_dirsapol" class="btn btn-primary"> ENTRAR</a>
    </div>
  </div>

  </div>
</div>
</form>
</form>
@include('frontend.includes.footer')
@endsection