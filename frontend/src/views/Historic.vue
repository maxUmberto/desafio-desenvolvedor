<template>
  <div v-if="user_historic.length > 0">
    <div class="card" v-for="historic in user_historic" :key="historic.id">
      <div class="card-body">
        <strong>Moeda de origem: </strong> {{historic.source_currency_name}} ({{ historic.source_currency_code }}) <br>
        <strong>Moeda de destino: </strong> {{historic.destination_currency_name}} ({{ historic.destination_currency_code }}) <br>
        <strong>Valor para conversão: </strong> $ {{ historic.source_currency_value }} <br>
        <strong>Forma de pagamento: </strong> {{ historic.payment_method.name }} <br>
        <strong>Valor da "Moeda de destino" usado para conversão: </strong> $ {{ historic.destination_currency_bid_value }} <br>
        <strong>Valor comprado em "Moeda de destino": </strong> $ {{ historic.destination_currency_total_bough_value }} <br>
        <strong>Taxa de pagamento: </strong> $ {{ historic.payment_method_tax_value }} <br>
        <strong>Taxa de conversão: </strong> $ {{ historic.exchange_tax_value }} <br>
        <strong>Valor utilizado para conversão descontando as taxas: </strong> $ {{ historic.exchange_used_value }}
      </div>
    </div>
  </div>
</template>

<script lang="ts">

import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useCookie } from 'vue-cookie-next';

export default ({
  name: 'Historic',

  setup() {
    const cookie = useCookie();

    const error = ref('');
    const user_historic = ref([]);

    onMounted(async () => {
      axios.get(process.env.VUE_APP_API_URL + '/exchange/historic', {
        headers: {
          Authorization: 'Bearer ' + cookie.getCookie('jwt')
        }
      })
      .then( async (response: any) => {
        user_historic.value = await response.data.data.user_historic;
      }).catch( async request_error => {
        console.log(request_error.response.data);
        error.value = "Houve um erro, favor tentar novamente";
      });
    });

    return {
      error,
      user_historic
    }
  }
})
</script>

<style scoped>
.card {
  margin-bottom: 10px;
}
</style>
