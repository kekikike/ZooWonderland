import './bootstrap';

// entrada principal de Vue
import { createApp } from 'vue';
import AppWrapper from './components/AppWrapper.vue';

const app = createApp(AppWrapper);

console.log('Vue app created with AppWrapper');

// Mount en el div con id="app" que Blade proporciona
const element = document.getElementById('app');
if (element) {
    app.mount('#app');
    console.log('Vue app mounted to #app');
} else {
    console.warn('Element #app not found in the DOM');
}

