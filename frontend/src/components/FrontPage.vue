<template>
  <div>
    <h1>{{ frontPage.title.rendered }}</h1>
    <div v-html="frontPage.content.rendered"></div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      frontPage: {
        title: {},
        content: {}
      }
    };
  },
  mounted() {
    this.fetchFrontPage();
  },
  methods: {
    fetchFrontPage() {
      axios
        .get('http://localhost/vue-wordpress/wp-json/wp/v2/pages?slug=home-page')
        .then(response => {
          this.frontPage = response.data[0];
        })
        .catch(error => {
          console.error(error);
        });
    },
  },
};
</script>
