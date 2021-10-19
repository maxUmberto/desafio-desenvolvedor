<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container-fluid">
      <router-link class="navbar-brand" to="/">Início</router-link>
      <div>
        <ul class="navbar-nav me-auto mb-2 mb-md-0" v-if="!auth">
          <li class="nav-item">
            <router-link class="nav-link active" to="/login">Login</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link active" to="/signup">Cadastrar</router-link>
          </li>
        </ul>
        <ul class="navbar-nav me-auto mb-2 mb-md-0" v-if="auth">
          <li class="nav-item">
            <router-link class="nav-link active" to="/historico">Histórico</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link active" to="/" @click="logout">Sair</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script lang="ts">

import axios from 'axios';
import { useStore } from 'vuex';
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useCookie } from 'vue-cookie-next';

export default {
  name: 'Navbar',

  setup() {
    const store = useStore();
    const cookie = useCookie();
    const router = useRouter();

    const auth = computed( () => store.state.authenticated );

    const logout = () => {
      axios.post(process.env.VUE_APP_API_URL + '/logout', {}, {
        headers: {
          Authorization: 'Bearer ' + cookie.getCookie('jwt')
        }
      }).finally(async () => {
        localStorage.removeItem('user');
        await cookie.removeCookie('jwt');
        await router.push('/login');
        await store.dispatch('setAuth', false);
      });
    }

    return {
      auth,
      logout
    }
  }
}
</script>
