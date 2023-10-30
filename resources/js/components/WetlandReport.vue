<template>
    <h1>Wetland Values Report</h1>
    <p>
        The wetland values report tool provides a summary of the waterbird diversity at a selected wetland. Dominant
        land uses are reported within 350m of the wetland boundary.
    </p>
    <template v-if="feature">
        <table class="table">
            <tbody>
            <tr>
                <td>Wetland name</td>
                <td>{{ wetlandName }}</td>
            </tr>
            <tr>
                <td>Protection Status</td>
                <td>{{ protectionStatus }}</td>
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

        <!--        <h2>BirdLife Australia records</h2>
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
                </table>-->

        <h2>Latham's snipe data</h2>
        <p>
            Latham's Snipe is a mysterious migratory waterbird (shorebird) that is cryptic in its plumage and behaviour.
            This means it is often overlooked in monitoring and assessment. As a result, its wetland habitats are
            frequently threatened by land use change.
        </p>

        <table class="table">
            <tbody>
            <tr>
                <td>Maximum number of Latham's Snipe</td>
                <td>
                    {{ maxSnipeSeasonLabel }}
                </td>
                <td>
                    <button
                        type="button"
                        class="btn btn-sm float-end"
                        @click="showSeasonalCountChart = !showSeasonalCountChart"
                        v-show="snipe.seasonalCounts.length && maxSnipeSeasonCount"
                    >
                        <i class="bi bi-bar-chart"></i>
                    </button>
                </td>
            </tr>
            <tr v-if="showSeasonalCountChart">
                <td colspan="3">
                    <seasonal-counts-chart :seasonal-counts="snipe.seasonalCounts" :index="1"/>
                </td>
            </tr>
            <tr>
                <td>Maximum number of Latham's Snipe (ALA)</td>
                <td>
                    {{ maxSnipeAlaSeasonLabel }}
                </td>
                <td>
                    <button
                        type="button"
                        class="btn btn-sm float-end"
                        @click="showAlaSeasonalCountChart = !showAlaSeasonalCountChart"
                        v-show="snipe.alaSeasonalCounts.length && maxSnipeAlaSeasonCount"
                    >
                        <i class="bi bi-bar-chart"></i>
                    </button>
                </td>
            </tr>
            <tr v-if="showAlaSeasonalCountChart">
                <td colspan="3">
                    <seasonal-counts-chart :seasonal-counts="snipe.alaSeasonalCounts" :index="2"/>
                </td>
            </tr>
            </tbody>
        </table>

        <h2>Land use</h2>
        <p>
            The built environment surrounding a wetland will influence its value for biodiversity. A wetland surrounded
            by housing or industry may provide less habitat for wetland birds than a wetland located near other
            wetlands. But it may also provide a refuge for birds transiting through an urban landscape.
        </p>
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
    </template>
</template>

<script>
import {GeoJSON, WKT} from 'ol/format';
import {values, pick, filter, intersection, maxBy, forIn, sortBy} from 'lodash';
import {buffer} from '@turf/turf';

import LandUseChart from './LandUseChart.vue';
import SeasonalCountsChart from './SeasonalCountsChart.vue';
import {getNumericFeatureId} from './ol-helpers';
import geoserverMixin from './geoserver-mixin';

import {mapActions} from 'vuex';

export default {
    name: 'WetlandReport',
    components: {SeasonalCountsChart, LandUseChart},
    props: ['feature', 'id'],
    mixins: [geoserverMixin],
    data() {
        return {
            landuseEndpointMap: {
                'Vicmap Planning Zones': 'planning/zones',
                'Vicmap Planning Overlays': 'planning/overlays',
                'VLUIS Property Classification': 'vluis/property',
                'VLUIS Land Use': 'vluis/alum',
                'VLUIS Land Cover': 'vluis/landcover',
                'Catchment Land use': 'catchment',
            },
            stateAbbreviations: {
                'Victoria': 'vic',
                'Queensland': 'qld',
                'New South Wales': 'nsw',
                'Tasmania': 'tas',
                'Australian Capital Territory': 'act',
                'Northern Territory': 'nt',
                'Western Australia': 'wa',
                'South Australia': 'sa',
            },
            content: '',
            alaSpecies: null,
            landuse: [],
            snipe: {
                seasonalCounts: [],
                alaSeasonalCounts: [],
            },
            showSeasonalCountChart: false,
            showAlaSeasonalCountChart: false,
        };
    },
    watch: {
        feature(feature) {
            if (feature) {
                this.renderWetlandInfo(feature);
            }
        },
    },
    computed: {
        wetlandName() {
            return this.feature.get('name');
        },

        protectionStatus() {
            if (this.feature === null || !this.feature.get('protection_status')) {
                return 'Unknown';
            } else if (this.feature.get('protection_status')[0] === null) {
                return 'None';
            } else {
                return this.feature.get('protection_status').join(', ');
            }
        },
        featureStateAbbreviations() {
            return values(pick(this.stateAbbreviations, this.feature.get('states')));
        },
        threatenedAlaSpecies() {
            let self = this;
            if (self.alaSpecies) {
                return filter(self.alaSpecies, function(specie) {
                    let targetStatuses = ['aus', ...self.featureStateAbbreviations];
                    let knownStatuses = Object.keys(specie.conservation);

                    return intersection(targetStatuses, knownStatuses).length > 0;
                });
            }
            return null;
        },
        maxSnipeSeasonCount() {
            if (this.snipe.seasonalCounts.length > 0) {
                return maxBy(this.snipe.seasonalCounts, (record) => parseInt(record.count));
            }
            return null;
        },
        maxSnipeAlaSeasonCount() {
            if (this.snipe.alaSeasonalCounts.length > 0) {
                return maxBy(this.snipe.alaSeasonalCounts, (record) => parseInt(record.count));
            }
            return null;
        },
        maxSnipeSeasonLabel() {
            let label = '?';
            if (this.snipe.seasonalCounts.length > 0) {
                label = 'No data';
                if (this.maxSnipeSeasonCount) {
                    label = this.maxSnipeSeasonCount.season + ': ' + this.maxSnipeSeasonCount.count;
                }
            }

            return label;
        },
        maxSnipeAlaSeasonLabel() {
            let label = '?';
            if (this.snipe.alaSeasonalCounts.length > 0) {
                label = 'No data';
                if (this.maxSnipeAlaSeasonCount) {
                    label = this.maxSnipeAlaSeasonCount.season + ': ' + this.maxSnipeAlaSeasonCount.count;
                }
            }

            return label;
        },
    },
    methods: {
        featureToWkt(feature, newProjection) {
            let wktOptions = {'featureProjection': 'EPSG:3857'};
            if (typeof newProjection != 'undefined') {
                wktOptions['dataProjection'] = 'EPSG:' + newProjection;
            }
            return (new WKT()).writeFeature(feature, wktOptions);
        },
        bufferedFeature(feature) {
            // TODO: convert bufferDistance to /app/config
            let bufferDistance = .350;
            let localFeature = feature.clone();
            localFeature.getGeometry().transform('EPSG:3857', 'EPSG:7844');
            let geojson = (new GeoJSON()).writeFeatureObject(localFeature);

            let bufferedFeature = buffer(geojson, bufferDistance, {
                'units': 'kilometers',
            });

            bufferedFeature = (new GeoJSON()).readFeature(bufferedFeature);
            bufferedFeature.getGeometry().transform('EPSG:7844', 'EPSG:3857');

            return bufferedFeature;
        },
        fetchAlaBirds(feature) {
            let self = this;
            if (feature) {
                axios.post('/app/area/ala-birds', {
                    'wkt': self.featureToWkt(feature, 7844),
                }).then(function(response) {
                    self.alaSpecies = response.data;
                });
            } else {
                self.alaSpecies = null;
            }
        },

        fetchLandUsage(feature) {
            let self = this;
            self.landuse.length = 0;
            if (feature) {
                forIn(self.landuseEndpointMap, (value, key) => {
                    self.fetchLandUsePercentage(feature, key, value);
                });
            }
        },

        async fetchLandUsePercentage(feature, label, endpoint) {
            let self = this;
            await axios.post('/app/landuse/' + endpoint, {
                'wkt': self.featureToWkt(self.bufferedFeature(feature), 7844),
            }).then(function(response) {
                self.landUsePushAndSort(
                    {
                        label: label,
                        data: response.data,
                    },
                );
            });
        },
        landUsePushAndSort(data) {
            let self = this;
            self.landuse.push(data);
            self.landuse = sortBy(self.landuse, 'label');
        },

        async fetchLathamsSnipeSeasonalCounts(feature) {
            let self = this;
            self.snipe.seasonalCounts.length = 0;
            if (feature) {
                self.snipe.seasonalCounts = await axios.post('/app/snipe/seasonal-counts', {
                    'wkt': self.featureToWkt(feature, 7844),
                }).then(function(response) {
                    return response.data;
                });
            }
        },
        async fetchSnipeAlaSeasonalCounts(feature) {
            let self = this;
            self.snipe.alaSeasonalCounts.length = 0;
            if (feature) {
                self.snipe.alaSeasonalCounts = await axios.post('/app/snipe/ala-seasonal-counts', {
                    'wkt': self.featureToWkt(feature, 7844),
                }).then(function(response) {
                    return response.data;
                });
            }
        },
        renderWetlandInfo(feature) {
            this.fetchAlaBirds(feature);
            this.fetchLathamsSnipeSeasonalCounts(feature);
            this.fetchSnipeAlaSeasonalCounts(feature);
            this.fetchLandUsage(feature);
        },
        ...mapActions([
            'storeWetland',
        ]),

    },
    mounted() {
        const self = this;

        if (self.feature && getNumericFeatureId(self.feature) === self.id) {
            self.renderWetlandInfo(self.feature);
        } else {
            const url = self.getWfsFeatureInfo('aurin', 'wetlands', self.id);
            self.storeWetland(url);
        }
    },

};

</script>

<style scoped>
h2 {
    margin-top: 2em;
}
</style>
