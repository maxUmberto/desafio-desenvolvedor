<template>
  <Navbar/>
  <router-view/>
  <Footer/>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';

export default defineComponent({
  name: 'App',
  components: {
    Navbar,
    Footer,
  },
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&display=swap');

body {
  margin: 0 auto;
  font-family: 'Roboto', sans-serif;
}

a {
  text-decoration: none;
}
</style>
