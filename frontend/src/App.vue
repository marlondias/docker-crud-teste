<template>
  <div class="main-container">
    <h1 class="app-title">Developers CRUD</h1>


    <List />
  </div>
</template>

<script>
import axios from 'axios'
import List from './components/DevelopersList/List.vue'
//import AlertMessagesContainer from './components/AlertMessagesContainer.vue'

const apiBaseUrl = 'http://localhost:8000/api';
//const apiBearerToken = 'myToken123';

export default {
  name: 'App',

  components: {
    List
  },

  data() {
    return {
      api: axios.create({
        baseURL: apiBaseUrl,
        timeout: 10000,
        //headers: {'Authorization': `Bearer ${apiBearerToken}`},
      }),
    }
  },

  methods: {
    createDeveloper() {
      this.api.post(`/developers`);
    },

    getDeveloperInfo(id) {
      this.api.get(`/developers/${id}`);
    },

    updateDeveloper(id) {
      this.api.put(`/developers/${id}`);
    },

    deleteDeveloper(id) {
      this.api.delete(`/developers/${id}`);
    },

    openModalDeveloperInfo() {
      alert('Modal info');
    },

    confirmDeveloperDelete() {
      alert('Certeza delete?');
    }
  },

}

</script>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;600;700&display=swap');
  *{
    margin:0;
    padding:0;
    box-sizing:border-box;
  } 
  #app {
    font-family: 'Quicksand', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    min-height: 100vh;
    background-color: #def4f0;
    xbackground-image: url('../assets/depositphotos_81589834-c.png');
    background-repeat: repeat;
    background-size: 200px;
  }
  button {
    font-size: 15px;
    padding: 5px 10px;
    text-align: center;
    border: 1px solid rgba(0, 0, 0, 0.5);
    border-radius: 0;
    background-color: rgba(255,255,255,0.3);
    cursor: pointer;
  }
  button:hover,
  button:focus {
    border-color: rgba(0, 0, 0, 0.6);
    background-color: rgba(255,255,255,0.5);
  }
  select {
    font-size: 15px;
    padding: 4px;
    border: 1px solid rgba(0, 0, 0, 0.6);
    background-color: rgba(255,255,255,0.4);
    cursor: pointer;
  }
  select:hover,
  select:focus {
    background-color: rgba(255,255,255,0.6);
  }
  .main-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 0;
  }
  .app-title {
    margin-bottom: 35px;
    text-align: center;
  }
</style>
