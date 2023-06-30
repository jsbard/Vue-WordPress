const express = require('express');
const serveStatic = require('serve-static');
const history = require('connect-history-api-fallback');
const path = require('path');

const app = express();

// Serve static files from the 'frontend/dist' directory
app.use(serveStatic(path.join(__dirname, 'frontend', 'dist')));

// Enable client-side routing fallback
app.use(history());

// Serve index.html for any route that doesn't match a static file
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, 'frontend', 'dist', 'index.html'));
});

// Start the server
const port = process.env.PORT || 8080;
app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
