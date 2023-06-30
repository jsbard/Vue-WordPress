<template>
  <div v-if="page">
    <h1>{{ page.title.rendered }}</h1>
    <div v-html="page.content.rendered"></div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      page: null, // Initialize with null instead of an empty object
    };
  },
  mounted() {
    this.fetchPage();
  },
  methods: {
    fetchPage() {
      const slug = this.$route.params.slug;
      axios
        .get(`http://localhost/vue-wordpress/wp-json/wp/v2/pages?slug=${slug}`)
        .then(response => {
          this.page = response.data[0];
        })
        .catch(error => {
          console.error(error);
        });
    },
  },
};
</script>
