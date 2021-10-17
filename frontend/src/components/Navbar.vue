<template>
  <nav class="navbar">
    <div class="logo">
      <!-- <img src="@/assets/logo.png" alt=""> -->
      <p>Logo</p>
    </div>
    <div class="navbar-itens">
      <router-link to="/">Início</router-link> |
      <router-link to="/historico">Histórico</router-link> |
      <router-link to="/configuracoes">Configurações</router-link> |
      <router-link to="/login">Login</router-link>

    </div>
  </nav>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'Navbar',

});
</script>

<style scoped>
nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 10px;
  background-color: pink;
}
</style>
