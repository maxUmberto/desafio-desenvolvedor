<template>
  <p v-for="(error, index) in errors" :key="index">
    {{ error }}
  </p>
  <form @submit.prevent="submit">
    <h1 class="h3 mb-3 fw-normal">Novo usuário</h1>
    
    <input v-model="data.first_name" type="text" class="form-control" placeholder="Nome">
    
    <input v-model="data.last_name" type="text" class="form-control" placeholder="Sobrenome">
    
    <input v-model="data.email" type="email" class="form-control" placeholder="Email">
    
    <input v-model="data.password" type="password" class="form-control" placeholder="Senha">
    
    <button class="w-100 btn btn-lg btn-primary" type="submit">Cadastrar</button>
  </form>
</template>

<script lang="ts">

import axios from 'axios';
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useCookie } from 'vue-cookie-next';
import { useStore } from 'vuex';

export default {
  name: 'SignUp',

  setup() {
    const router = useRouter();
    const cookie = useCookie();
    const store = useStore();

    const data = reactive({
      first_name: '',
      last_name: '',
      email: '',
      password: '',
    });

    const errors: string[] = reactive([]);

    const checkStatus = (error: any) => {
      if(error.status === 422) {
        const request_errors = Object.values(error.data.errors);
        
        request_errors.map( (error: any) => {
          errors.push(error[0]);
        })
      }
      else {
        console.log(error);
        errors.push('Houve um problema no cadastro. Contate os administradores')
      }
    }
    
    const submit = async () => {

      axios.post(process.env.VUE_APP_API_URL + '/sign-up', {
        first_name: data.first_name,
        last_name: data.last_name,
        email: data.email,
        password: data.password,
      }).then( async (response: any) => {
        await localStorage.setItem('user', JSON.stringify(response.data.user));
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