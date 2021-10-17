import { createRouter, createWebHashHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import SignUp from '../views/SignUp.vue';
import Historic from '../views/Historic.vue';
import Settings from '../views/Settings.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/registrar',
    name: 'SignUp',
    component: SignUp,
  },
  {
    path: '/historico',
    name: 'Historic',
    component: Historic,
  },
  {
    path: '/configuracoes',
    name: 'Settings',
    component: Settings,
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes, // short for `routes: routes`
});

export default router;
