<template>
  <div class="caixa-opcoes">
    <div>
      <span>Ordenar por</span>
      <select v-model="orderByColumn">
        <option value="">Data de cadastro</option>
        <option value="nome">Nome</option>
        <option value="sexo">Sexo</option>
        <option value="data_nascimento">Data de Nascimento</option>
        <option value="idade">Idade</option>
        <option value="hobby">Hobby</option>
      </select>
      <input id="ordemInversa" type="checkbox" v-model="orderByDescending">
      <label for="ordemInversa">decrescente</label>
    </div>
    <div>
      <span>Por página</span>
      <select v-model="pageSize">
        <option value="5">5</option>
        <option value="10">10</option>
        <option value="15">15</option>
        <option value="20">20</option>
        <option value="25">25</option>
        <option value="30">30</option>
        <option value="40">30</option>
        <option value="50">50</option>
      </select>
    </div>
  </div>
</template>

<script>
export default {
  emits: ['display-controls-changed'],

  data() {
    return {
      pageSize: 10,
      orderByColumn: '',
      orderByDescending: false,
    }
  },

  watch: {
    pageSize() {
      this.emitDisplayControlsChanged();
    },
    orderByColumn() {
      this.emitDisplayControlsChanged();
    },
    orderByDescending() {
      this.emitDisplayControlsChanged();
    },
  },

  methods: {
    getIndexQueryString() {
      let query = '';
      query += `&page_size=${this.pageSize}`;
      if (this.orderByColumn) {
        query += `&order_by=${this.orderByColumn}`;
        if (this.orderByDescending) {
          query += `&order_by_direction=DESC`;
        }
      }
      return query;
    },
    emitDisplayControlsChanged() {
      this.$emit('display-controls-changed', {query: this.getIndexQueryString()});
    }
  },

}
</script>

<style scoped>
* {
  user-select: none;
}
select {
  font-size: 13px;
  margin-left: 8px;
}
input[type="checkbox"] {
  margin: 4px 5px 0 10px;
  width: 15px;
  height: 15px;
  cursor: pointer;
}
label {
  cursor: pointer;
}
.caixa-opcoes {
  display: flex;
  font-size: 15px;
  padding: 10px;
  justify-content: space-between;
}
</style>