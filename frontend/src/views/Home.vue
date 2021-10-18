<template>
  {{ message }}
</template>

<script lang="ts">

import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useCookie } from 'vue-cookie-next';
import { useStore } from 'vuex';

export default {
  name: 'Home',

  setup() {
    const cookie = useCookie();
    const store = useStore();

    const message = ref('Você não está logado')
    onMounted(async () => {
      axios.post(process.env.VUE_APP_API_URL + '/login', {
        email: 'teste@teste.com',
        password: 123456
      }, {
        headers: {
          Authorization: 'Bearer ' + cookie.getCookie('jwt')
        }
      })
      .then( async (response: any) => {
        message.value = "Você logou";
        await store.dispatch('setAuth', true);
      }).catch( async error => {
        message.value = "Você não está logado";
        await store.dispatch('setAuth', false);
      });
    });

    return {
      message
    }
  }
}
</script>

<style scoped>

</style>