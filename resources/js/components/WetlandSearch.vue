<script>
import {autocomplete} from "@algolia/autocomplete-js";
import '@algolia/autocomplete-theme-classic';

export default {
    data() {
        return {
            'wetlands': null
        }
    },
    computed: {
        features() {
            return this.$store.getters.wetlandFeatures;
        }
    },
    watch: {
        features() {
            this.wetlands = this.features.map(function (feature) {
                return {'name': feature.get('wetland_name'), 'feature': feature}
            });
        }
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
                self.$store.commit('selectWetland', item.feature)
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
