<template>
    <div>
      <canvas ref="chartCanvas"></canvas>
    </div>
  </template>
  
  <script lang="ts">
  import { defineComponent, onMounted, ref } from 'vue';
  import { Chart, registerables } from 'chart.js';
  
  Chart.register(...registerables);
  
  export default defineComponent({
    name: 'BarChart',
    props: {
      data: {
        type: Array as () => { hora: number; total_credito: string; total_debito: string }[],
        required: true
      }
    },
    setup(props) {
      const chartCanvas = ref<HTMLCanvasElement | null>(null);
  
      onMounted(() => {
        if (chartCanvas.value) {
          const labels = props.data.map(item => `${item.hora}:00`);
          const creditValues = props.data.map(item => parseFloat(item.total_credito));
          const debitValues = props.data.map(item => parseFloat(item.total_debito));
  
          new Chart(chartCanvas.value, {
            type: 'bar',
            data: {
              labels,
              datasets: [
                {
                  label: 'Total Crédito',
                  data: creditValues,
                  backgroundColor: 'rgba(54, 162, 235, 0.7)'
                },
                {
                  label: 'Total Débito',
                  data: debitValues,
                  backgroundColor: 'rgba(255, 99, 132, 0.7)'
                }
              ]
            },
            options: {
              responsive: true,
              plugins: {
                legend: {
                  position: 'top'
                },
                title: {
                  display: true,
                  text: 'Créditos e Débitos por Hora'
                }
              },
              scales: {
                x: {
                  title: {
                    display: true,
                    text: 'Hora do Dia'
                  }
                },
                y: {
                  title: {
                    display: true,
                    text: 'Valores (R$)'
                  },
                  beginAtZero: true
                }
              }
            }
          });
        }
      });
  
      return {
        chartCanvas
      };
    }
  });
  </script>
  
  <style scoped>
  canvas {
    max-width: 100%;
  }
  </style>
  