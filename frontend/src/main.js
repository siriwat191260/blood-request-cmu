import { createApp } from 'vue';
import App from './App.vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js'; // Include Bootstrap JavaScript
import $ from 'jquery';

// Make Bootstrap and jQuery available globally


import routes from './router/routes'


const app = createApp(App);
app.config.globalProperties.$ = $;
app.use(routes);

app.mount('#app');

