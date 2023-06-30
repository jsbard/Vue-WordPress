<template>
  <div>
    <h2>Blog</h2>
    <ul>
      <li v-for="post in blogPosts" :key="post.id">
        <h3>{{ post.title.rendered }}</h3>
        <p>Date: {{ formatDate(post.date) }}</p>
        <div v-html="post.excerpt.rendered"></div>
        <router-link :to="'/post/' + post.id">Read More</router-link>
      </li>
    </ul>
  </div>
</template>


<script>
import axios from 'axios';

export default {
  name: 'BlogView',
  data() {
    return {
      blogPosts: [],
    };
  },
  mounted() {
    this.fetchBlogPosts();
  },
  methods: {
    fetchBlogPosts() {
      axios
        .get('http://localhost/vue-wordpress/wp-json/wp/v2/posts')
        .then(response => {
          this.blogPosts = response.data;
        })
        .catch(error => {
          console.error(error);
        });
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString();
    },
  },
};
</script>
