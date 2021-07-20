<template>
  <div class="container">
    <div class="caixa-busca">
      <input type="text" v-model="searchTerm" placeholder="what are you searching for?">
      <button @click.stop="emitSearchRequested">Buscar</button>
    </div>
  </div>
</template>

<script>
export default {
  emits: ['search-requested'],

  data() {
    return {
      searchTerm: '',
    }
  },

  methods: {
    emitSearchRequested() {
      this.searchTerm = this.searchTerm.trim();
      this.$emit('search-requested', {query: `&search_term=${this.searchTerm}`});
    },
  }

}
</script>

<style scoped>
.container {
  padding: 10px;
  text-align: center;
}
.caixa-busca {
  display: flex;
  max-width: 500px;
  margin: auto;
}
input {
  font-size: 15px;
  padding: 5px;
  border: 1px solid rgba(0, 0, 0, 0.6);
  border-right: 0;
  background-color: rgba(255,255,255,0.4);
  flex-grow: 1;
}


</style>