 

<script>  
      // Obtener el canvas y contexto
      var ctxBar = document.getElementById('myChartBar').getContext('2d'); 
      // Datos del gráfico
      var dataBar = {
        labels: ['18/04/2023', '19/04/2023', '20/04/2023', '21/04/2023', '21/04/2023', '21/04/2023', '21/04/2023',
        '18/04/2023', '19/04/2023', '20/04/2023' ] ,
        datasets: [ 
          {
            label: 'PA',
            data:   [[135,  85], [80, 122],[80,121],[78 ,118],[70,120],[65, 115],[80,145],[83,130] ,[90,140],[85,138]],
            borderColor: 'blue',
            backgroundColor: 'blue',
            fill: false
          } 
        ] 
      };
   
      // Opciones del gráfico
      var optionsBar =  { 
          maintainAspectRatio: false,
          responsive: true,
          plugins: {
                datalabels: { // This code is used to display data values
                    color: 'blue',
                    anchor: 'end',
                    align: 'top',
                    formatter: Math.round,
                    font: { 
                        weight: 'bold',
                        size: 12
                    },
                    formatter: function(value, context) {  
                        return context.chart.data.datasets[0].data[context.dataIndex];
                    }
                },
            legend: {
              display: false
            }
          },
          scales: { 
            x: {
              title: {
                display: true,
                text: 'Fechas de Consulta'
              }
            },
            y: {
              title: {
                display: true,
                text: 'Escala PA'
              },
              min: 0,
              max: 320,
              ticks: { 
                stepSize: 15
              }
            } 
          } 
      };
        
      function cargar_grafico_pa(){
        console.log('cargar_grafico_pa');
        var myChart =new Chart(ctxBar, {
            type: 'bar',
            data: dataBar,
            options: optionsBar
        });
      } 
  
</script>