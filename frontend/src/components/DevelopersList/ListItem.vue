<template>
  <div class="item">
    <div class="content">
      <h3>{{developer.nome}}</h3>
      <span>Sexo: <strong>{{developer.sexo_extenso}}</strong></span>
      <span>Nascimento: <strong>{{developer.data_nascimento_br}}</strong></span>
      <span>Idade: <strong>{{developer.idade}}</strong></span>
    </div>
    <div class="actions">
      <button class="btn btn-info" @click.stop="handleClickShowInfo">
        <i class="fas fa-fw fa-info"></i>
      </button>
      <button class="btn btn-info" @click.stop="handleClickEdit">
        <i class="fas fa-fw fa-edit"></i>
      </button>
      <button class="btn btn-danger" @click.stop="handleClickDelete">
        <i class="fas fa-fw fa-trash-alt"></i>
      </button>
    </div>
  </div>
</template>

<script>
export default {

  emits: ['developer-show-info', 'developer-edit', 'developer-delete'],

  props: {
    developer: {
      type: Object,
      required: true,
    }
  },

  methods: {
    handleClickShowInfo() {
      this.$emit('developer-show-info', {developerId: this.developer.id});
    },
    handleClickEdit() {
      this.$emit('developer-edit', {developerId: this.developer.id});
    },
    handleClickDelete() {
      this.$emit('developer-delete', {developerId: this.developer.id});
    },
  }

}
</script>

<style scoped>
.item {
  display: flex;
  flex-flow: row nowrap;
  border: 1px solid rgba(0,0,0,0.2);
  border-radius: 8px;
  overflow: hidden;
  padding: 8px 10px;
  margin-bottom: 10px;
  background-color: rgba(0,0,0,0.2);
  color: rgba(0,0,0,0.75);
}
.item:focus,
.item:hover {
  background-color: rgba(0,0,0,0.1);
  color: rgba(0,0,0,0.9);
}

.content {
  flex-grow: 1;
}
.content h3 {
  margin-bottom: 5px;
}
.content span {
  margin-right: 15px;
}

.actions > button {
  margin-left: 10px;
  margin-bottom: 5px;
}

.btn {
  text-align: center;
  font-size: 20px;
  padding: 8px;
  border: 2px solid rgba(0,0,0,0.1);
  border-radius: 50px;
  color: rgba(0,0,0,0.6);
}
.btn:hover {
  border-color: rgba(0,0,0,0.3);
  color: rgba(0,0,0,0.9);
}
.btn-info {
  background-color: rgb(127, 192, 245);
}
.btn-danger {
  background-color: rgb(241, 145, 145);
}
</style>