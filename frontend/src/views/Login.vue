<template>
  <h1>Login</h1>
  <Form/>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Form from '../components/login-components/Form.vue';

export default defineComponent({
  name: 'Login',
  components: {
    Form,
  },
});
</script>
