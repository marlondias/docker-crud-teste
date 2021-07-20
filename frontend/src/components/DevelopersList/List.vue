<template>
  <div class="background-color-blend">
    <DisplayControls @display-controls-changed="updateQueryDisplay" />
    <SearchControls @search-requested="updateQuerySearch" />
    <div class="caixa-botao-cadastro">
      <button @click.stop="$emit('developer-create')"><i class="fas fa-plus"></i>&nbsp;&nbsp;Novo</button>
    </div>
    <div v-show="isLoading" class="loading">
      <img src="../../assets/loading.gif">
    </div>
    <div v-show="!isLoading">
      <div v-if="developers.length > 0">
        <ul>
          <li v-for="developer in developers" :key="developer.id">
            <ListItem :developer="developer" 
              @developer-show-info="$emit('developer-show-info', $event)"
              @developer-edit="$emit('developer-edit', $event)"
              @developer-delete="$emit('developer-delete', $event)" />
          </li>
        </ul>
        <PaginationControls 
          v-if="metadata !== null" 
          :pagination-data="metadata.pagination" 
          @current-page-changed="updateQueryPagination" />
      </div>
      <div v-else class="sem-itens">
        Não foram encontrados itens para exibição.
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import DisplayControls from './DisplayControls.vue'
import SearchControls from './SearchControls.vue'
import PaginationControls from './PaginationControls.vue'
import ListItem from './ListItem.vue'

export default {
  components: { 
    DisplayControls, 
    SearchControls, 
    PaginationControls, 
    ListItem
  },

  emits: ['api-feedback', 'developer-create', 'developer-show-info', 'developer-edit', 'developer-delete'],

  data() {
    return {
      api: axios.create({
        baseURL: process.env.VUE_APP_API_BASE_URL,
        timeout: 10000,
      }),
      isLoading: false,
      developers: [],
      metadata: null,
      queryDisplay: '',
      querySearch: '',
      queryPagination: '',
    }
  },

  methods: {
    updateQueryDisplay(event) {
      this.queryDisplay = event.query;
      this.triggerListUpdate();
    },

    updateQuerySearch(event) {
      this.querySearch = event.query;
      this.queryPagination = '';
      this.triggerListUpdate();
    },

    updateQueryPagination(event) {
      this.queryPagination = event.query;
      this.triggerListUpdate();
    },

    triggerListUpdate() {
      let fullQuery = `${this.queryDisplay}${this.queryPagination}${this.querySearch}`;
      let uri = '/developers';
      if (fullQuery.length > 0) {
        uri += `?${fullQuery}`.replace('?&', '?');
      }
      this.isLoading = true;
      this.api.get(uri)
      .then((response) => {
        this.developers = response.data.data;
        this.metadata = response.data.metadata;
      })
      .catch((error) => {
        this.developers = [];
        this.metadata = null;
        let statusCode = 500;
        let errorContent = null;
        if (error.response) {
          statusCode = error.response.status;
          errorContent = (error.response.data) ? error.response.data.error : null;
        }
        this.$emit('api-feedback', {status: statusCode, content: errorContent});
      })
      .then(() => {
        this.isLoading = false;
      });
    },
  },

  mounted() {
    this.triggerListUpdate();
  },

}
</script>

<style scoped>
ul {
  padding: 25px 10px 10px 10px;
}
.caixa-botao-cadastro {
  text-align: right;
  padding: 0 10px;
}
.caixa-botao-cadastro button {
  background-color: rgb(127, 192, 245);
}
.sem-itens {
  text-align: center;
  color: rgba(0,0,0,0.4);
  padding: 50px 30px;
  font-weight: bold;
}
.loading {
  margin: 50px 0;
  padding: 30px;
  text-align: center;
}
.loading img {
  max-width: 100px;
  height: auto;
}

</style>