import axios from 'axios';

// configuración global de Axios con CSRF (Laravel) y cabeceras comunes
axios.defaults.headers.common['X-CSRF-TOKEN'] = document
    .querySelector('meta[name="csrf-token"]')
    ?.getAttribute('content') || '';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

export default axios;