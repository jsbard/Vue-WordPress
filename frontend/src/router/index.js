import { createRouter, createWebHistory } from 'vue-router';
import BlogView from '@/components/BlogView.vue';
import ContactView from '@/components/ContactView.vue';
import FrontPage from '@/components/FrontPage.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: FrontPage,
  },
  {
    path: '/blog',
    name: 'Blog',
    component: BlogView,
  },
  {
    path: '/contact',
    name: 'Contact',
    component: ContactView,
  },
  {
    path: '/home-page',
    redirect: '/',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
