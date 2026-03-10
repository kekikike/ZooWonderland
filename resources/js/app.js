import './bootstrap';

// entrada principal de Vue
import { createApp } from 'vue';
import RecorridoForm from './views/RecorridoForm.vue';

try {
    const app = createApp({
        template: `
            <div>
                <recorrido-form :recorrido-id="recorridoId"></recorrido-form>
            </div>
        `,
        data() {
            return {
                recorridoId: null // Se puede pasar desde Blade
            }
        }
    });

    // Registrar componente global
    app.component('recorrido-form', RecorridoForm);

    console.log('Vue app created');

    app.mount('#app');
    
    console.log('Vue app mounted successfully');
} catch (error) {
    console.error('Error initializing Vue app:', error);
}

