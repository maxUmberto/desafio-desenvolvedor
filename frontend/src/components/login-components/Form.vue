<template>
  <form @submit.prevent="onSubmit">
    <div class="form-error" v-if="error.message !== ''">
      <p>{{error.message}}</p>
    </div>
    <div class="form-group my-2">
      <label for="">Email</label>
      <input
        v-model="form.email"
        type="email"
        class="form-control"
        placeholder="Email"
        required>
    </div>
    <div class="form-group my-2">
      <label for="">Senha</label>
      <input
          v-model="form.password"
          type="password"
          class="form-control"
          placeholder="********"
          required>
    </div>
    <button class="btn btn-success btn-block my-2" type="submit">
      Logar
    </button>
    <router-link to="/registrar" >Cadastre-se</router-link>
  </form>
</template>

<script lang="ts">
import { defineComponent, reactive } from 'vue';
import AuthService from '../../services/authentication';

export default defineComponent({
  name: 'Form',
  setup() {
    const form = reactive({
      email: '',
      password: '',
    });

    const error = reactive({
      message: '',
    });

    const onSubmit = async () => {
      const response: any = await AuthService.login(form);
      error.message = response;
    };

    return { error, form, onSubmit };
  },
});
</script>
