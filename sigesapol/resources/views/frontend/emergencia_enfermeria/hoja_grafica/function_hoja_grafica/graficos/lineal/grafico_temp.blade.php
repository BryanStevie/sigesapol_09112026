<script>
  Chart.register(ChartDataLabels); 

  // Obtener el canvas y contexto
  var ctxLine = document.getElementById('myChart').getContext('2d');

  // Datos del gráfico
  var dataLine = {
  labels: 
  ['MAÑANA', 'TARDE', 'NOCHE', 'MAÑANA','TARDE','NOCHE'],
  datasets: [
      {
        label: 'RESP',
        data: [40,30,35,37,28.30],
        borderColor: 'skyblue',
        backgroundColor: 'skyblue',
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
          color: 'skyblue',
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
      text: 'FRECUENCIA RESPIRATORIA'
    },
    min: 0,
    max: 40,
    ticks: { 
      stepSize: 10
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