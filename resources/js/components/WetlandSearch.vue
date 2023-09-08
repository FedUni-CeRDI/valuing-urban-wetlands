<script>
import {autocomplete} from "@algolia/autocomplete-js";
import '@algolia/autocomplete-theme-classic';
import geoserverMixin from '@/components/geoserver-mixin';
import {mapActions, mapState} from 'vuex';

export default {
    mixins: [geoserverMixin],
    data() {
        return {}
    },
    computed: {
        ...mapState({
            wetlands: 'wetlandNames',
        }),
        ...mapState([
            'selectedWetland'
        ])
    },
    methods: {
      ...mapActions([
          'storeWetland'
      ])
    },

    mounted() {
        let self = this;
        let source = {
            sourceId: 'wetlands',
            templates: {
                item({item}) {
                    return item.name;
                },
            },
            getItems({query}) {
                return self.wetlands.filter(function (item) {
                    return item.name.match(new RegExp(query, 'i'))
                });
            },
            getItemInputValue({item}) {
                return item.name;
            },
            onSelect({item}) {
                if (!self.selectedWetland || self.selectedWetland.getId() !== 'wetlands.' + item.id) {

                    const url = self.getWfsFeatureInfo('aurin', 'wetlands', item.id);
                    self.storeWetland(url);
                }
            }
        };

        autocomplete({
            container: '#wetland-search',
            placeholder: 'Search by Wetland Name',
            getSources({query}) {
                return [source]
            }
        })
    }
}

</script>

<template>
    <div id="wetland-search"/>
</template>

<style scoped>

</style>
