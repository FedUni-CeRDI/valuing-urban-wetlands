<template>
    <h1>Wetland Values Report</h1>
    <template v-if="feature">
        <table class="table">
            <tbody>
            <tr>
                <td>Wetland name</td>
                <td>{{ feature.wetland_name }}</td>
            </tr>
            <tr>
                <td>Protection Status</td>
                <td>{{ protectionStatus }}</td>
            </tr>
            </tbody>
        </table>

        <h2>Land use</h2>
        <table class="table">
            <tbody>
            <tr v-if="!landuse.length">
                <td>?</td>
            </tr>
            <tr v-else>
                <td colspan="2">
                    <table class="w-100">
                        <thead>
                        <tr>
                            <th>Source / Primary classification</th>
                            <th>%</th>
                        </tr>
                        </thead>
                        <tbody v-for="(usage, key) in landuse">
                        <tr>
                            <td colspan="2">
                                <land-use-chart :landUseData="usage" :key="usage.label" :index="key"></land-use-chart>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>

        <h2>ALA Records</h2>
        <table class="table">
            <tbody>
            <tr>
                <td>Number of waterbird species</td>
                <td>{{ alaSpecies == null ? '?' : alaSpecies.length }}</td>
            </tr>
            <tr>
                <td>Number of threatened waterbird species</td>
                <td>{{ threatenedAlaSpecies == null ? '?' : threatenedAlaSpecies.length }}</td>
            </tr>
            </tbody>
        </table>

        <h2>BirdLife Australia records</h2>
        <table class="table">
            <tbody>
            <tr>
                <td>Reporting rate</td>
                <td>[TBA]</td>
            </tr>
            <tr>
                <td>Breeding metric</td>
                <td>[TBA]</td>
            </tr>
            <tr>
                <td>Conservation metric</td>
                <td>[TBA]</td>
            </tr>
            <tr>
                <td>Condition</td>
                <td>[TBA]</td>
            </tr>
            </tbody>
        </table>

        <h2>Latham's snipe data</h2>
        <table class="table">
            <tbody>
            <tr>
                <td>Maximum number of Latham's Snipe</td>
                <td>[TBA]</td>
            </tr>
            </tbody>
        </table>
    </template>
</template>

<script>
import {GeoJSON, WKT} from 'ol/format';
import LandUseChart from "./LandUseChart.vue";
import _ from 'lodash';

export default {
    name: 'WetlandReport',
    components: {LandUseChart},
    data() {
        return {
            landuseEndpointMap: {
                'Vicmap Planning Zones': 'planning/zones',
                'Vicmap Planning Overlays': 'planning/overlays',
                'VLUIS Property Classification': 'vluis/property',
                'VLUIS Land Use': 'vluis/alum',
                'VLUIS Land Cover': 'vluis/landcover',
                'Catchment Land use': 'catchment'
            },
            content: '',
            alaSpecies: null,
            landuse: [],
        };
    },
    computed: {
        feature() {
            this.fetchAlaBirds(this.$store.getters.featureProperties)
            this.fetchLandUsage(this.$store.getters.featureProperties)
            return this.$store.getters.featureProperties;
        },
        protectionStatus() {
            if (!this.feature.hasOwnProperty('capad_status') || this.feature.capad_status[0] === null) {
                return 'None';
            } else {
                return this.feature.capad_status.join(', ');
            }
        },
        threatenedAlaSpecies() {
            if (this.alaSpecies) {
                return _.filter(this.alaSpecies, function (specie) {
                    // TODO: This shouldn't be hardcoded. Should be able to determine which state it is in
                    return specie.conservation_aus || specie.conservation_vic;
                });
            }
            return null;
        },

    },
    methods: {
        geometryToWkt(featureProperties, newProjection) {
            if (featureProperties) {
                let feature = (new GeoJSON()).readFeature(featureProperties.geojson);
                let wktOptions = {'featureProjection': 'EPSG:4283'};
                if (typeof newProjection != 'undefined') {
                    wktOptions['dataProjection'] = 'EPSG:' + newProjection;
                }
                return (new WKT()).writeFeature(feature, wktOptions);
            }
            return null;
        },

        fetchAlaBirds(feature) {
            if (feature) {
                let self = this;
                axios.get('/app/area/ala-birds', {
                    params: {
                        'wkt': self.geometryToWkt(feature),
                    },
                }).then(function (response) {
                    self.alaSpecies = response.data;
                });
            } else {
                this.alaSpecies = null;
            }
        },

        fetchLandUsage(feature) {
            let self = this;
            this.landuse = [];
            if (feature) {
                _.forIn(self.landuseEndpointMap, (value, key) => {
                    self.fetchLandUsePercentage(feature, key, value)
                });
            }
        },

        fetchLandUsePercentage(feature, label, endpoint) {

            let self = this;
            axios.get('/app/landuse/' + endpoint, {
                params: {
                    'wkt': self.geometryToWkt(feature),
                },
            }).then(function (response) {
                self.landUsePushAndSort(
                    {
                        label: label,
                        data: response.data
                    }
                );
            });

        },
        landUsePushAndSort(data) {
            let self = this;
            self.landuse.push(data);
            self.landuse = _.sortBy(self.landuse, 'label');
        },
    },
    mounted() {
        const self = this;
        self.content = self.$route.params.id;
    },

};

</script>

<style scoped>


</style>
