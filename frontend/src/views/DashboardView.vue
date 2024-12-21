<script lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import axios from 'axios';
import BarChart from "../components/BarChart.vue";

export default defineComponent({
  name: 'DashboardView',
  components: {
    BarChart
  },
  setup() {
    const dashboardData = ref<any>(null);
    const error = ref<string | null>(null);

    function formatNumber(value: any): string {
      const numValue = parseFloat(value);
      if (isNaN(numValue)) {
        return '0,00';
      } 
      const roundedValue = numValue.toFixed(2);
      const formattedValue = new Intl.NumberFormat('pt-BR').format(parseFloat(roundedValue));
      const parts = formattedValue.split(',');
      if (parts.length === 1) {
        return `${formattedValue},00`;
      }
      return formattedValue;
    }

    function formatDate(dateString: string): string {
      const [year, month, day] = dateString.split("-");
      return `${day}/${month}/${year}`;
    }

    onMounted(() => {
      const link = document.createElement('link');
      link.rel = 'stylesheet';
      link.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css';
      document.head.appendChild(link);

      axios.get("http://localhost:8081/api/dashboard")
        .then(response => {
          console.log('Dados:', response.data);
          dashboardData.value = response.data;
          console.log('Dados2:', dashboardData.value);
        })
        .catch(err => {
          error.value = 'Erro ao carregar dados do dashboard.';
          console.error(err);
        });
    });

    // Retorne a função formatNumber para usá-la no template
    return {
      dashboardData,
      error,
      formatNumber,
      formatDate
    };
  },
});

</script>
<template>
  <div class="container mt-5">
    <div v-if="error" class="alert alert-danger" role="alert">
      {{ error }}
    </div>
    <div v-else>
      <div class="alert alert-success" role="alert">
        <h1>DashBoard</h1>
      </div>
      <div v-if="!dashboardData" class="row">
        <div class="col">
          <div class="d-flex justify-content-center">
            <div class="spinner-grow text-success custom-spinner" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        </div>
      </div>
      <div v-if="dashboardData && dashboardData.RelacaoSomaQuantidade" class="row">
        <div class="col-12 col-md-6 " >
          <h3>Relação Soma Quantidade</h3>
          <div style="height: 600px; overflow-y: auto;">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Cooperativa</th>
                  <th>Agência</th>
                  <th>Quantidade de movimentações</th>
                  <th>Total de Valores(R$)</th>
                  <th>Valor por Movimentação (R$) </th>
                </tr>
              </thead>
              <tbody>
                <!-- Iterando sobre os itens em RelacaoSomaQuantidade.original -->
                <tr v-for="(item, index) in dashboardData.RelacaoSomaQuantidade.original" :key="index">
                  <td>{{ item.cooperativa }}</td>
                  <td>{{ item.agencia }}</td>
                  <td>{{ item.quantidade }}</td>
                  <td>{{ formatNumber(item.total_valores) }}</td>
                  <td>{{ formatNumber(item.relacao) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="col-12 col-md-6 ">
          <div class="row p-4 my-4">
            <div class="col-sm-6 col-md-12 p-1">
              <div class="card" style="width: 100%;">
                <div class="card-body">
                  <h5 class="card-title">Agências com maior número de Movimentações</h5>
                  <ol>
                    <li 
                      v-for="(item, index) in dashboardData.AgenciaMaiorMovimentacao.original.slice(0, 5)" 
                      :key="index">
                      Coop: {{ item.cooperativa }} Agência: {{ item.agencia }} Movimentações: {{ item.quantidade }}
                    </li>
                  </ol>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-12 p-1">
              <div class="card" style="width: 100%;">
                <div class="card-body">
                  <h5 class="card-title">Agências com Maior Valor de Movimentado</h5>
                  <ol>
                    <li 
                      v-for="(item, index) in dashboardData.AgenciaSomaMovimentacao.original.slice(0, 5)" 
                      :key="index">
                      Coop: {{ item.cooperativa }} Agência: {{ item.agencia }}  Valor:R$ {{ formatNumber(item.total_valores) }}
                    </li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="dashboardData" class="row my-4">
        <div class="col-12 col-md-6">
          <div class="card" style="width: 100%;">
              <div class="card-body">
                <h5 class="card-title">Datas Relevantes</h5>
                <h6>Data com Maior Valor Movimentado</h6>
                <ul>
                    <li>Data: {{ formatDate(dashboardData.dataMaiorSomaMovimentacao.original.data) }}</li>
                    <li>Valor Movimentado: R${{ formatNumber(dashboardData.dataMaiorSomaMovimentacao.original.total_movimentacao) }}</li>
                </ul>
                <h6>Data com Menor Valor Movimentado</h6>
                <ul>
                  <li>Data: {{ formatDate(dashboardData.dataMenorSomaMovimentacao.original.data) }}</li>
                  <li>Valor Movimentado: R${{ formatNumber(dashboardData.dataMenorSomaMovimentacao.original.total_movimentacao) }}</li>
                </ul>
                <h6>Data com Mais Movimentação</h6>
                <ul>
                  <li>Data: {{ formatDate(dashboardData.dataMaisMovimentacao.original.data) }}</li>
                  <li>Quantidade de Movimentações: {{(dashboardData.dataMaisMovimentacao.original.quantidade) }}</li>
                </ul>
                <h6>Data com Menos Movimentação</h6>
                <ul>
                  <li>Data: {{ formatDate(dashboardData.dataMenosMovimentacao.original.data) }}</li>
                  <li>Quantidade de Movimentações: {{(dashboardData.dataMenosMovimentacao.original.quantidade) }}</li>
                </ul>
              </div>
          </div>
        </div>
        <div class="col-12 col-md-6" >
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Dias Da Semana Relevantes</h5>
              <h6>Dia da Semana com mais movimentações tipo X18 </h6>
              <ul>
                <li v-if="dashboardData.diaMaisX18.original.quantidade"> Dia da Semana: {{dashboardData.diaMaisX18.original.dia_da_semana}}</li>
                <li v-if="dashboardData.diaMaisX18.original.quantidade"> Quantidade de Movimentações: {{dashboardData.diaMaisX18.original.quantidade}}</li>
                <li v-if="!dashboardData.diaMaisX18.original.quantidade"> Não há movimentações do tipo X18.</li>
              </ul>
              <h6>Dia da Semana com mais movimentações tipo PX1 </h6>
              <ul>
                <li v-if="dashboardData.diaMaisPX1.original.quantidade"> Dia da Semana: {{dashboardData.diaMaisPX1.original.dia_da_semana}}</li>
                <li v-if="dashboardData.diaMaisPX1.original.quantidade"> Quantidade de Movimentações: {{dashboardData.diaMaisPX1.original.quantidade}}</li>
                <li v-if="!dashboardData.diaMaisPX1.original.quantidade"> Não há movimentações do tipo PX1.</li>
              </ul>
              <h6>Dia da Semana com mais movimentações tipo RX1 </h6>
              <ul>
                <li v-if="dashboardData.diaMaisRX1.original.quantidade"> Dia da Semana: {{dashboardData.diaMaisRX1.original.dia_da_semana}}</li>
                <li v-if="dashboardData.diaMaisRX1.original.quantidade"> Quantidade de Movimentações: {{dashboardData.diaMaisRX1.original.quantidade}}</li>
                <li v-if="!dashboardData.diaMaisRX1.original.quantidade"> Não há movimentações do tipo PX1.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row my-4" v-if="dashboardData">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Gráfico de Montante Movimentado por Hora</h5>
              <BarChart :data="dashboardData.CreditoDebitoPorHora.original" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>



<style scoped>
  .custom-spinner {
    width: 60vw;  /* Aumentando o tamanho */
    height: 60vw; /* Aumentando o tamanho */
  }
</style>
