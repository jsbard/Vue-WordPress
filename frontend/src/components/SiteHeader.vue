<template>
  <header>
    <nav>
      <ul>
        <li v-for="menuItem in menuItems" :key="menuItem.ID">
          <a :href="generateAbsoluteUrl(menuItem.url)">{{ menuItem.title }}</a>
        </li>
      </ul>
    </nav>
  </header>
</template>

<script>
export default {
  data() {
    return {
      menuItems: [],
      baseUrl: 'http://localhost:8080', // Update the base URL to your desired value
    };
  },
  mounted() {
    this.fetchMenuItems();
  },
  methods: {
    fetchMenuItems() {
      // Make the API request to fetch the primary menu items
      // Replace 'your-api-url' with the actual API endpoint URL
      fetch('http://localhost/vue-wordpress/wp-json/primary-menu-endpoint/v1/primary-menu')
        .then((response) => response.json())
        .then((data) => {
          this.menuItems = data;
        })
        .catch((error) => {
          console.error('Error fetching menu items:', error);
        });
    },
    generateAbsoluteUrl(relativeUrl) {
      if (relativeUrl.startsWith('http://localhost/vue-wordpress')) {
        return this.baseUrl + relativeUrl.substr('http://localhost/vue-wordpress'.length);
      } else {
        return relativeUrl;
      }
    },
  },
};
</script>
