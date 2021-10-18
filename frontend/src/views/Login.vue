<template>
  <p v-for="(error, index) in errors" :key="index">
    {{ error }}
  </p>
  <form @submit.prevent="submit">
    <h1 class="h3 mb-3 fw-normal">Login</h1>
    <input type="email" v-model="data.email" class="form-control" placeholder="Email">
    
    <input type="password" v-model="data.password" class="form-control" placeholder="Senha">
    
    <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
  </form>
</template>

<script lang="ts">

import axios from 'axios';
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useCookie } from 'vue-cookie-next';
import { useStore } from 'vuex';

export default {
  name: 'Login',

  setup() {

    const router = useRouter();
    const cookie = useCookie();
    const store = useStore();
    

    const data = reactive({
      email: '',
      password: '',
    });

    const errors: string[] = reactive([]);

    const checkStatus = (error: any) => {
      errors.length = 0;
      if(error.status === 422) {
        const request_errors = Object.values(error.data.errors);
        
        request_errors.map( (error: any) => {
          errors.push(error[0]);
        })
      }
      else if(error.status === 404) {
        errors.push('Usuário não encontrado');
      }
      else {
        console.log(error);
        errors.push('Houve um problema no cadastro. Contate os administradores')
      }
    }

    const submit = async () => {

      axios.post(process.env.VUE_APP_API_URL + '/login', {
        email: data.email,
        password: data.password,
      }).then( async (response: any) => {
        await cookie.setCookie('jwt', response.data.token)
        await store.dispatch('setAuth', true);
        await router.push('/');
      }).catch( error => {
        checkStatus(error.response);
      });
    }

    return {
      errors,
      data,
      submit
    }
  }
}
</script>

<style scoped>

</style>