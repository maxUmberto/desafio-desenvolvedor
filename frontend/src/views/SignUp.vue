<template>
  <h1>signup</h1>
  <Form/>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import Form from '../components/login-components/Form.vue';

export default defineComponent({
  name: 'SignUp',
  components: {
    Form,
  },
});
</script>
