<script>
    
    const checkbox = document.getElementById("cvc_invasivoskardex");
    checkbox.addEventListener("click", function() { 
        if (checkbox.checked) { 
            swal({
            buttons: true,
            title: "¿Desea Suspender el Procedimiento Seleccionado?",
            text: "Recuerde verificar que todos los datos sean correctos.",
            icon: "warning",
            dangerMode: true,
            }).then((willDelete) => {
            if (willDelete) { 
                 
                swal({
                buttons: false,
                title: "Registro Exitoso",
                text: "El medicamento fue Suspendido de forma Correcta.",
                icon: "success",
                timer: 1500,
                });

            } else {
                checkbox.checked = false;
                swal({
                buttons: false,
                title: "Operación Cancelada",
                text: "Proceso de Registro Cancelado.",
                icon: "error",
                timer: 1500,
                });

            }
            });
        }  
    });  
    
</script>