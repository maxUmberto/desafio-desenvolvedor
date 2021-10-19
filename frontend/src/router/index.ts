import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import store from '../store';

import Home from '@/views/Home.vue';
import Login from '@/views/Login.vue';
import SignUp from '@/views/SignUp.vue';
import Historic from '@/views/Historic.vue';

const routes: Array<RouteRecordRaw> = [
  {
    path: '',
    component: Home,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/historico',
    component: Historic,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/signup',
    component: SignUp
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach(async (to, from, next) => {

  const auth = await store.state.authenticated;
  const requiresAuth = to.matched.some( record => record.meta.requiresAuth );

  if( requiresAuth && !auth) next({name: 'Login'});
  else next();
})

export default router
