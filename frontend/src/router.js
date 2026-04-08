import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import UnitTests from './components/UnitTests.vue';

const routes = [
  { path: '/', redirect: '/analysis' },
  { path: '/analysis', component: Home },
  { path: '/unit-tests', component: UnitTests },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;