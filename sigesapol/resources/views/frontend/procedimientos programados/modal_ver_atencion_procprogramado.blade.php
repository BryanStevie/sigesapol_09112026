@if(\Request::input('embebido') == 1)
@include('frontend.procedimientos programados.partials.pp_atencion_contenido')
<script>
  if (typeof registrarAuditoriaPP === 'function') registrarAuditoriaPP('Ver Atención Proc. Prog.', {{ $solicitud->id_tipo_atencion ? intval($solicitud->id_tipo_atencion) : 'null' }});
</script>
@else
<script>var PP_ES_MODAL = true;</script>
@include('frontend.procedimientos programados.partials.pp_atencion_contenido')
@include('frontend.procedimientos programados.partials.pp_atencion_assets')
@endif
