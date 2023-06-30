import { createRouter, createWebHistory } from 'vue-router';
import BlogView from '@/components/BlogView.vue';
import ContactView from '@/components/ContactView.vue';
import FrontPage from '@/components/FrontPage.vue';
import PageView from '@/components/PageView.vue';

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
    path: '/:slug', // Use a dynamic segment to capture any page slug
    name: 'Page',
    component: PageView,
    props: true,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
