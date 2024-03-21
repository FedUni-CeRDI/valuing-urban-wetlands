<script>
import {mapActions, mapState, mapMutations} from 'vuex';
import Dropdown from 'primevue/dropdown';

import { ref } from "vue";

export default {
  name: 'FilteredResults',
  methods: {
    ...mapMutations([
      'updateFilteredWetland',
    ]),

    filterValueChanged(index){
     this.updateFilteredWetland(index.target.value);
    },
  },
  computed: {
    ...mapState([
      'dropDownObject',
    ]),
  },
  props: {

  },
  watch: {
    ...mapState([
      'dropDownObject',
    ]),
  },
  emits: [],
  data() {
    return{
      filteredList:['land1','land2','land3']
    };
  },
  mounted() {
  },
};
</script>

<template>
  <label for="filterBox">Quick Filter Results</label>
  <select @change="filterValueChanged($event)" v-model="key" id="filterBox">
    <option disabled value="">Please select one</option>
    <option v-for="(item, index) in  this.dropDownObject"  v-bind:value="index" >{{item}}</option>
  </select>
</template>