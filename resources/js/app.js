
import './bootstrap';

import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import './assets/styles.css';
import CKEditor from '@mayasabha/ckeditor4-vue3';

import { createApp } from 'vue';
import {createRouter, createWebHistory} from 'vue-router';
import Routes from './routes.js';
import Login from './pages/auth/Login.vue';
// import Home from './smb/Home.vue';
import PageContent from './smb/layout/PageContent.vue';
import useUser from './user.js';
import store from './stores/store.js'; // Import the Vuex store
// import SMBNavbar from './smb/layout/SMBNavbar.vue';
import Navbar from './components/Navbar.vue';
import VueLazyLoad from 'vue3-lazy';
import { defineAsyncComponent } from 'vue';

const app = createApp({
    setup() {
        const { fetchUser } = useUser();
        fetchUser();
    }
});

app.use(VueLazyLoad, {
  loading: '/storage/slides/1.jpg', // Placeholder image while loading
  error: '/storage/slides/2.jpg', // Error image if loading fails
});

app.config.globalProperties.$adjustFontSize = (adjustment) => {
    const root = document.documentElement;
    const currentFontSize = parseFloat(getComputedStyle(root).getPropertyValue('--base-font-size'));
    root.style.setProperty('--base-font-size', `${currentFontSize + adjustment}px`);
  };
  
  app.config.globalProperties.$resetFontSize = () => {
    const root = document.documentElement;
    root.style.setProperty('--base-font-size', `16px`); // Default base font size
  };
  
  app.provide('adjustFontSize', app.config.globalProperties.$adjustFontSize);
  app.provide('resetFontSize', app.config.globalProperties.$resetFontSize);

const router = createRouter({
    routes: Routes,
    history : createWebHistory()
});

app.use(router);
app.use( CKEditor );
app.use(store); // Register the store

app.provide('folderPath', window.folderPath);

// app.component('SMBNavbar', SMBNavbar);
app.component('Login', Login);
app.component('Navbar', Navbar);  //admin Navbar
// app.component('Home', Home);
// app.component('PageContent', PageContent);
// app.component('SMBNavbar', () => import(`./${window.folderPath}/layout/SMBNavbar.vue`).catch(() => console.error("Failed to load SMBNavbar component")));
// app.component('Home', () => import(`./${window.folderPath}/Home.vue`).catch(() => console.error("Failed to load Home component")));
// app.component('PageContent', () => import(`./smb/layout/PageContent.vue`).catch(() => console.error("Failed to load PageContent component")));
app.component('PageContent', defineAsyncComponent(() =>
  import(`./${window.folderPath}/layout/PageContent.vue`).catch(() => {
      console.error("Failed to load PageContent component");
      // return import('./smb/layout/PageContent.vue'); // Fallback to a default component if the dynamic import fails
  })
));
app.mount('#app');
