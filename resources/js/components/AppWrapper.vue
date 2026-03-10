<template>
  <component :is="currentComponent" :recorrido-id="recorridoId" />
</template>

<script>
import RecorridoList from '../views/RecorridoList.vue';
import RecorridoForm from '../views/RecorridoForm.vue';

export default {
  name: 'AppWrapper',
  components: {
    RecorridoList,
    RecorridoForm
  },
  data() {
    return {
      currentComponent: 'RecorridoList',
      recorridoId: null
    };
  },
  mounted() {
    const path = window.location.pathname;
    
    if (path.includes('/recorridos/crear')) {
      this.currentComponent = 'RecorridoForm';
      this.recorridoId = null;
    } else if (path.includes('/recorridos/editar')) {
      this.currentComponent = 'RecorridoForm';
      const params = new URLSearchParams(window.location.search);
      this.recorridoId = params.get('id');
    } else if (path.includes('/recorridos')) {
      this.currentComponent = 'RecorridoList';
    }
  }
};
</script>
