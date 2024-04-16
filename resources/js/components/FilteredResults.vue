<script>
import {mapActions, mapState, mapMutations} from 'vuex';

import {ref} from "vue";

export default {
  name: 'FilteredResults',
  methods: {
    ...mapMutations([
      'updateFilteredWetland',
    ]),

    filterValueChanged(index) {
      if (this.validValue(index)) {
        this.updateFilteredWetland(index.target.value);
      } else {
        alert("Please select only 1 wetland at a time");
      }
    },
    validValue(index) {
      var select = index.target;
      var result = [];
      var options = select && select.options;
      var opt;
      for (var i = 0, iLen = options.length; i < iLen; i++) {
        opt = options[i];
        if (opt.selected) {
          result.push(opt.value || opt.text);
        }
      }
      if (result.length == 1) {
        return true;
      } else {
        return false;
      }
    }
  },
  computed: {
    ...mapState([
      'dropDownObject',
    ]),
  },
  props: {},
  watch: {
    ...mapState([
      'dropDownObject',
    ]),
  },
};
</script>

<template>
  <!--  <label for="filterBox">Quick Filter Results</label>-->
  <select @change="filterValueChanged($event)" v-model="key" id="filterBox" multiple>
    <option disabled value="">Quick Filter Results</option>
    <option v-for="(item, index) in  this.dropDownObject" v-bind:value="index">{{ item }}</option>
  </select>
</template>