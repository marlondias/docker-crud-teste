<template>
  <div class="pagination">
    <button :disabled="(currentPage == 1)" @click.stop="previousPage">
      <i class="fas fa-chevron-left"></i>&nbsp;&nbsp;Anterior
    </button>
    <div class="page-selector">
      <strong>Página</strong>
      <select v-model="currentPage">
        <option v-for="(page, index) of pages" :key="index" :value="page">{{page}}</option>
      </select>
      <strong>de {{totalPages}}</strong>
    </div>
    <button :disabled="(currentPage == totalPages)" @click.stop="nextPage">Próxima&nbsp;<i class="fas fa-chevron-right"></i></button>
  </div>
</template>

<script>
export default {
  emits: ['current-page-changed'],

  props: {
    paginationData: {
      type: Object,
      required: true,
    }
  },

  data() {
    return {
      totalPages: this.paginationData.last_page,
      currentPage: this.paginationData.current_page,
    }
  },

  computed: {
    pages() {
      let pageNumbers = [...Array(this.totalPages+1).keys()];
      pageNumbers.shift();
      return pageNumbers;
    },
  },

  watch: {
    paginationData() {
      this.totalPages = this.paginationData.last_page;
      this.currentPage = this.paginationData.current_page;
    },
    currentPage() {
      this.emitCurrentPageChanged();
    }

  },

  methods: {
    previousPage() {
      this.currentPage--;
    },
    nextPage() {
      this.currentPage++;
    },
    emitCurrentPageChanged() {
      this.$emit('current-page-changed', {query: `&page=${this.currentPage}`});
    },
  },

}
</script>

<style scoped>
.pagination {
  display: flex;
  font-size: 16px;
  padding: 10px;
}
.page-selector {
  flex-grow: 1;
  text-align: center;
  color: rgba(0,0,0,0.8);
}
.page-selector select {
  background-color: transparent;
  margin: 0 10px;
}
.page-selector select:hover,
.page-selector select:focus {
  background-color: rgba(255,255,255,0.3);
}
</style>