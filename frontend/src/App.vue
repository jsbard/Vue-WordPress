<template>
  <div id="app">
    <SiteHeader />
    <router-view />
  </div>
</template>

<script>
import SiteHeader from './components/SiteHeader.vue';

export default {
  name: 'App',
  components: {
    SiteHeader,
  },
};
</script>
