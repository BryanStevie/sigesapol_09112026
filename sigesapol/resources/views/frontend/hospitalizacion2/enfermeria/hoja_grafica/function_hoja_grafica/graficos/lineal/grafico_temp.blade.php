<script>
  Chart.register(ChartDataLabels); 

  // Obtener el canvas y contexto
  var ctxLine = document.getElementById('myChart').getContext('2d');

  // Datos del gráfico
  var dataLine = {
  labels: 
  ['18/04/2023', '19/04/2023', '20/04/2023', '21/04/2023', '21/04/2023', '21/04/2023', '21/04/2023',
  '18/04/2023', '19/04/2023', '20/04/2023', '21/04/2023', '21/04/2023', '21/04/2023', '21/04/2023',
  '18/04/2023', '19/04/2023', '20/04/2023', '21/04/2023', '21/04/2023', '21/04/2023', '21/04/2023' ],
  datasets: [
      {
        label: 'RESP',
        data: [20,  23, 25, 28,30,22,25 ,30,22,25,25, 28,30,22, 28,30,22,25 ,30,22,25],
        borderColor: 'blue',
        backgroundColor: 'blue',
        fill: false 
      } 
    ] 
  };

  // Opciones del gráfico
  var optionsLine = { 
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
      text: 'Escala RESP'
    },
    min: 10,
    max: 70,
    ticks: { 
      stepSize: 5
    }
  } 
  } 
};

        
    function cargar_grafico_temp(){
        console.log('cargar_grafico_temp');
        var myChart = new Chart(ctxLine, {
        type: 'line',
        data: dataLine,
        options: optionsLine
      });
    } 
    
</script>