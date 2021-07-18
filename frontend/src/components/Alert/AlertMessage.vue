<template>
    <div class="alert" :class="(color || '')">
      <h3>{{message}}</h3>
      <a role="button" class="close" @click.stop="closeAlert">&times;</a>
    </div>
</template>

<script>
export default {
  props: {
    'id' : {
      type: Number,
      required: true,
    },
    'message' : {
      type: String,
      required: true,
    },
    'color' : {
      type: String,
      required: false,
    }
  },

  emits: ['dismissed'],

  methods: {
    closeAlert() {
      this.$emit('dismissed', {id: this.id});
    }
  },

}
</script>

<style>
.alert{
  margin: 20px auto;
  padding: 20px 50px 20px 15px;
  position: relative;
  border-radius: 6px;
  box-shadow:0 0 15px 4px rgba(0,0,0,0.2);
  background-color: #ebebeb;
  border-left:5px solid rgba(0, 0, 0, 0.3);
  color: rgba(0, 0, 0, 0.55);
}
.alert.success {
  background-color: #a8f0c6;
}
.alert.warning {
  background-color: #ffd48a;
}
.alert.danger {
  background-color: #f7a7a3;
}

.close{
  position: absolute;
  font-size: 1.6em;
  width: 1.25em;
  height: 1.25em;
  text-align:center;
  line-height: 1;
  border: 1px solid rgba(0,0,0,0.3);
  border-radius:50%;
  color: rgba(0, 0, 0, 0.4);
  right: 15px;
  top:15px;
  cursor:pointer;
}
.close:hover {
  border-color: rgba(0,0,0,0.6);
  color: rgba(0, 0, 0, 0.6);
  background-color: rgba(0,0,0,0.1);

}
</style>