<template>
  <div class="background-color-blend">
    <DisplayControls @displayControlsChanged="updateQueryDisplay" />
    <SearchControls @searchRequested="updateQuerySearch" />
    <div class="caixa-botao-cadastro">
      <button><i class="fas fa-plus"></i>&nbsp;&nbsp;Novo</button>
    </div>
    <div v-if="developers.length > 0">
      <ul>
        <li v-for="developer in developers" :key="developer.id">
          <ListItem :developer="developer" />
        </li>
      </ul>
      <PaginationControls v-if="metadata !== null" :pagination-data="metadata.pagination" @currentPageChanged="updateQueryPagination" />
    </div>
    <div v-else class="sem-itens">
      Não foram encontrados itens para exibição.
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

  emits: ['apiFeedback', 'developerShowInfo', 'developerDelete'],

  props: {},

  data() {
    return {
      api: axios.create({
        baseURL: process.env.VUE_APP_API_BASE_URL,
        timeout: 10000,
      }),
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
        this.$emit('apiFeedback', {status: statusCode, content: errorContent});
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
</style>