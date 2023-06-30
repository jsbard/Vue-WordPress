<template>
  <div v-if="post">
    <h1>{{ post.title.rendered }}</h1>
    <div v-html="post.content.rendered"></div>
  </div>
  <div v-else>
    <p>Loading...</p>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['id'],
  data() {
    return {
      post: null,
    };
  },
  mounted() {
    this.fetchPost();
  },
  methods: {
    fetchPost() {
      axios
        .get(`http://localhost/vue-wordpress/wp-json/wp/v2/posts/${this.id}`)
        .then(response => {
          this.post = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
  },
};
</script>
