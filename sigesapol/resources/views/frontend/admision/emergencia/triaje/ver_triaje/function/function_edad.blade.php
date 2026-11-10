<script> 
  function calcularEdadPersona(anio, mes, dia, $tipo_elemento, $edad_detallada, $edad) { 
    const fecha = new Date(); 
    var curyear = fecha.getFullYear();
    var curmon = fecha.getMonth() + 1; 
    var curday =fecha.getDate()

    var calday = dia;
    var calmon = mes;
    var calyear = anio; 

    var curd = new Date(curyear,curmon-1,curday);
    var cald = new Date(calyear,calmon-1,calday);

    var diff = Date.UTC(curyear,curmon,curday,0,0,0) - Date.UTC(calyear,calmon,calday,0,0,0);

    var dife = datediff(curd,cald); 
    if($tipo_elemento=='input'){
      $('#'+$edad).val(''); 
      $('#'+$edad_detallada).val(''); 
      if($edad_detallada!=''){
        $('#'+$edad_detallada).val(dife[0]+" AÑOS, "+dife[1]+" MESES Y "+dife[2]+" DÍAS"); 
      }
      if($edad!=''){ 
        $('#'+$edad).val(dife[0]);  
      } 
    }else{ 
      if($edad_detallada!=''){
        $('#'+$edad_detallada).html(dife[0]+" AÑOS, "+dife[1]+" MESES Y "+dife[2]+" DÍAS"); 
      }

      if($edad!=''){
        $('#'+$edad).val(dife[0]);  
      } 
    }   
  } 
  function calcularEdadPersonaReturn(fecha_nac) { 
      var calday = '';
      var calmon = '';
      var calyear = ''; 
    if(fecha_nac != null && fecha_nac != undefined && fecha_nac.length>8 ){
      var oldDate = fecha_nac; //20/04/1946
      var values = oldDate.split("/");
      if(values.length==1){
        var values = oldDate.split("-");
         calday =values[2];
         calmon = values[1];
         calyear = values[0]; 
      }else{
         calday = values[0];
         calmon = values[1];
         calyear = values[2]; 
      } 

      const fecha = new Date(); 
      var curyear = fecha.getFullYear();
      var curmon = fecha.getMonth() + 1; 
      var curday =fecha.getDate()
 
      var curd = new Date(curyear,curmon-1,curday);
      var cald = new Date(calyear,calmon-1,calday);

      var diff = Date.UTC(curyear,curmon,curday,0,0,0) - Date.UTC(calyear,calmon,calday,0,0,0);

      var dife = datediff(curd,cald);  
      return dife[0]+" AÑOS, "+dife[1]+" MESES Y "+dife[2]+" DÍAS";  
    }
  } 
    
  function datediff(date1, date2) {
      var y1 = date1.getFullYear(), m1 = date1.getMonth(), d1 = date1.getDate(),
    y2 = date2.getFullYear(), m2 = date2.getMonth(), d2 = date2.getDate();

      if (d1 < d2) {
          m1--;
          d1 += DaysInMonth(y2, m2);
      }
      if (m1 < m2) {
          y1--;
          m1 += 12;
      }
      return [y1 - y2, m1 - m2, d1 - d2];
  }
  function DaysInMonth(Y, M) {
      with (new Date(Y, M, 1, 12)) {
          setDate(0);
          return getDate();
      }
  }

</script>