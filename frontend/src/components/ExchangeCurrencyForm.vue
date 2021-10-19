<template>
  <p v-for="(exchange_error, index) in exchange_errors" :key="index">
    {{ exchange_error }}
  </p>
  <form @submit.prevent="exchangeCurrency" class="row gy-2 gx-3 align-items-center">
    <div class="col-3">
      <label class="visually-hidden" for="autoSizingSelect">Moeda de Origem</label>
      <select v-model="data.source_currency_code" class="form-select" id="autoSizingSelect">
        <option value="" selected disabled>Moeda de origem</option>
        <option 
          v-for="(available_currency, index) in available_currencies" 
          :key="index"
          :value="available_currency.currency_code"
        >
          {{ available_currency.currency_code }} - {{ available_currency.currency_name }}
        </option>
      </select>
    </div>
    <div class="col-3">
      <label class="visually-hidden" for="autoSizingSelect">Moeda de Compra</label>
      <select v-model="data.destination_currency_code" class="form-select" id="autoSizingSelect">
        <option value="" selected disabled>Moeda de compra</option>
        <option 
          v-for="(available_currency, index) in available_currencies" 
          :key="index"
          :value="available_currency.currency_code"
        >
          {{ available_currency.currency_code }} - {{ available_currency.currency_name }}
        </option>
      </select>
    </div>
    <div class="col-3">
      <label class="visually-hidden" for="autoSizingSelect">Forme de pagamento</label>
      <select v-model="data.payment_method_id" class="form-select" id="autoSizingSelect">
        <option value="" selected disabled>Forme de pagamento</option>
        <option 
          v-for="payment_method in payment_methods" 
          :key="payment_method.id"
          :value="payment_method.id"
        >
          {{ payment_method.name }}
        </option>
      </select>
    </div>
    <div class="col-2">
      <label class="visually-hidden" for="autoSizingInput">Valor</label>
      <input v-model="data.source_currency_value" type="number" class="form-control" id="autoSizingInput" placeholder="R$">
    </div>
    <div class="col-1">
      <button type="submit" class="btn btn-primary">Converter</button>
    </div>
  </form>
  <br>

  <div class="card" v-if="Object.keys(exchange_currency_response).length > 0">
    <div class="card-body">
      <strong>Moeda de origem: </strong> {{ exchange_currency_response.source_currency_code }} <br>
      <strong>Moeda de destino: </strong> {{ exchange_currency_response.destination_currency_code }} <br>
      <strong>Valor para conversão: </strong> $ {{ exchange_currency_response.source_currency_value }} <br>
      <strong>Forma de pagamento: </strong> {{ exchange_currency_response.paymenth_method }} <br>
      <strong>Valor da "Moeda de destino" usado para conversão: </strong> $ {{ exchange_currency_response.destination_currency_bid_value }} <br>
      <strong>Valor comprado em "Moeda de destino": </strong> $ {{ exchange_currency_response.destination_currency_total_bough_value }} <br>
      <strong>Taxa de pagamento: </strong> $ {{ exchange_currency_response.payment_method_tax_value }} <br>
      <strong>Taxa de conversão: </strong> $ {{ exchange_currency_response.exchange_tax_value }} <br>
      <strong>Valor utilizado para conversão descontando as taxas: </strong> $ {{ exchange_currency_response.exchange_used_value }}
    </div>
  </div>
</template>

<script lang="ts">

import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useCookie } from 'vue-cookie-next';
import { reactive } from 'vue';

export default({
  name: 'ExchangeCurrencyForm',

  setup() {
    const cookie = useCookie();

    const exchange_errors: string [] = reactive([]);
    const available_currencies = ref([]);
    const payment_methods = ref([]);
    const exchange_currency_response = ref({});

    const data = reactive({
      source_currency_code: '',
      destination_currency_code: '',
      source_currency_value: '',
      payment_method_id: ''
    });

    onMounted(async () => {
      axios.get(process.env.VUE_APP_API_URL + '/exchange/currencies', {
        headers: {
          Authorization: 'Bearer ' + cookie.getCookie('jwt')
        }
      })
      .then( async (response: any) => {
        available_currencies.value = response.data.data.available_currencies;
        payment_methods.value = response.data.data.payment_methods;
      }).catch( async request_error => {
        console.log(request_error.response.data);
        // error.value = "Houve um erro, favor tentar novamente";
      });
    });

    const exchangeCurrency = () => {
      axios.post(process.env.VUE_APP_API_URL + '/exchange/simulate', {
        source_currency_code: data.source_currency_code,
        destination_currency_code: data.destination_currency_code,
        source_currency_value: data.source_currency_value,
        payment_method_id: data.payment_method_id
      }, {
        headers: {
          Authorization: 'Bearer ' + cookie.getCookie('jwt')
        }
      })
      .then( async (response: any) => {
        exchange_currency_response.value = response.data.data;
      }).catch( async request_error => {
        exchange_currency_response.value = {}
        checkStatus(request_error.response);
      });
    }

    const checkStatus = (error: any) => {
      console.log(error);
      exchange_errors.length = 0;
      if(error.status === 422) {
        const request_errors = Object.values(error.data.errors);
        
        request_errors.map( (error: any) => {
          exchange_errors.push(error[0]);
        })
      }
      else if(error.status === 404 || error.status === 500) {
        exchange_errors.push('Não é possível converter entre essas moedas. Por favor, selecione outras');
      }
      else {
        console.log(error);
        exchange_errors.push('Houve um problema na conversão. Contate os administradores')
      }
    }

    return {
      exchange_errors,
      available_currencies,
      payment_methods,
      data,
      exchangeCurrency,
      exchange_currency_response
    }
  }
})
</script>
