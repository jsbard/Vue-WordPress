<template>
    <div v-if="post">
      <h1>{{ post.title.rendered }}</h1>
      <div v-html="post.content.rendered"></div>
  
      <h2>Comments</h2>
      <ul>
        <li v-for="comment in post.comments" :key="comment.id">
          <strong>{{ comment.author_name }}</strong> - {{ comment.date }}
          <div v-html="comment.content.rendered"></div>
        </li>
      </ul>
  
      <form @submit.prevent="submitComment">
        <h3>Add a Comment</h3>
        <div>
          <label for="name">Name:</label>
          <input type="text" id="name" v-model="comment.author_name" required>
        </div>
        <div>
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="comment.author_email" required>
        </div>
        <div>
          <label for="content">Comment:</label>
          <textarea id="content" v-model.trim="comment.content" required></textarea>
        </div>
        <div>
          <button type="submit">Submit Comment</button>
        </div>
      </form>
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
        comment: {
          author_name: '',
          author_email: '',
          content: '',
        },
      };
    },
    mounted() {
      this.fetchPost();
    },
    methods: {
      fetchPost() {
        axios
          .get(`http://localhost/vue-wordpress/wp-json/wp/v2/posts/${this.id}?_embed=true`)
          .then(response => {
            this.post = response.data;
            this.fetchComments();
          })
          .catch(error => {
            console.error(error);
          });
      },
      fetchComments() {
        axios
          .get(`http://localhost/vue-wordpress/wp-json/wp/v2/comments?post=${this.id}`)
          .then(response => {
            this.post.comments = response.data;
          })
          .catch(error => {
            console.error(error);
          });
      },
      submitComment() {
        const { author_name, author_email, content } = this.comment;
        const commentData = {
          author_name,
          author_email,
          content,
          post: this.id,
          status: 'approved',
        };

        // Encode the application password credentials
        const username = 'root';
        const password = 'XyASamzAeXpeGWqAXcoy4Q6g';
        const credentials = `${username}:${password}`;
        const encodedCredentials = btoa(credentials);

        axios
            .post(`http://localhost/vue-wordpress/wp-json/wp/v2/comments?post=${this.id}`, commentData, {
                headers: {
                'Content-Type': 'application/json',
                Authorization: `Basic ${encodedCredentials}`,
                },
            })
          .then(() => {
            this.comment.author_name = '';
            this.comment.author_email = '';
            this.comment.content = '';
  
            this.fetchComments();
          })
          .catch(error => {
            console.error(error);
          });
      },
    },
  };
  </script>
  