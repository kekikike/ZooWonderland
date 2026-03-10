<template>
  <div class="checkbox-group">
    <button
      v-for="area in areas"
      :key="area.id_area"
      type="button"
      @click="$emit('toggle', area.id_area)"
      :class="['area-btn', selected.includes(area.id_area) ? 'selected' : '']"
    >
      {{ area.nombre }}
    </button>
  </div>
</template>

<script>
export default {
  name: 'AreaButtons',
  props: {
    areas: {
      type: Array,
      default: () => []
    },
    selected: {
      type: Array,
      default: () => []
    }
  }
};
</script>

<style scoped>
.area-btn {
  margin: 0.25rem;
  padding: 0.5rem 1rem;
  border: 1px solid #ccc;
  border-radius: 6px;
  background: #f5f5f5;
  cursor: pointer;
  transition: background 0.3s, color 0.3s;
}
.area-btn.selected {
  background: var(--selva-light);
  color: white;
  border-color: var(--selva-dark);
}
</style>