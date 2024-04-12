<template>
    <h1 id="weather-heading">Wetland Values Report</h1>
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
            <tr>
              <td>Land Use</td>
              <td><a href="#land-use-section" class="link-success">Detailed Info</a></td>
<!--   Commented out for time being          <td>{{ landUsePurpose == null ? '?' : landUsePurpose }}</td>-->
            </tr>
            </tbody>
        </table>


        <h2>ALA Records</h2>
        <table class="table">
            <tbody>
            <tr>
                <th colspan="3">Waterbirds</th>
            </tr>
            <tr>
                <td>{{ alaWaterbirdSpecies == null ? '?' : alaWaterbirdSpecies.length }} species</td>
                <td>{{ threatenedAlaWaterbirdSpecies == null ? '?' : threatenedAlaWaterbirdSpecies.length }}
                    threatened
                </td>
                <td class="text-end" @click="showWaterbirdList = true">
                    <button class="btn btn-light btn-sm" title="View species listing/info"><i
                        class="bi bi-list-columns"></i></button>
                </td>
            </tr>
            <tr>
                <th colspan="3">Frogs</th>
            </tr>
            <tr>
                <td>{{ alaFrogSpecies == null ? '?' : alaFrogSpecies.length }} species</td>
                <td>{{ threatenedAlaFrogSpecies == null ? '?' : threatenedAlaFrogSpecies.length }} threatened</td>
                <td class="text-end" @click="showFrogList = true">
                    <button class="btn btn-light btn-sm" title="View species listing/info"><i
                        class="bi bi-list-columns"></i></button>
                </td>
            </tr>
            </tbody>
        </table>

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
        <table class="table" id="land-use-section">
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
        <species-list :visible="showWaterbirdList" :species-list="alaWaterbirdSpecies" title="Waterbirds"
                      id="list-waterbirds" @closed="showWaterbirdList = false"/>
        <species-list :visible="showFrogList" :species-list="alaFrogSpecies" title="Frogs" id="list-frogs"
                      @closed="showFrogList = false"/>
    </template>
</template>

<script>
import {GeoJSON, WKT} from 'ol/format';
import {values, pick, filter, intersection, maxBy, forIn, sortBy} from 'lodash';

import LandUseChart from './LandUseChart.vue';
import SeasonalCountsChart from './SeasonalCountsChart.vue';
import {getNumericFeatureId} from './ol-helpers';
import geoserverMixin from './geoserver-mixin';

import {mapActions, mapMutations, mapState} from 'vuex';
import SpeciesList from './SpeciesList.vue';

export default {
    name: 'WetlandReport',
    components: {SpeciesList, SeasonalCountsChart, LandUseChart},
    props: ['feature', 'id'],
    mixins: [geoserverMixin],
    data() {
        return {
            showWaterbirdList: false,
            showFrogList: false,
            landuseEndpointMap: {
                'Wetland in buffer (%)': 'wetlands',
                'Vicmap Planning Zones': 'planning/zones',
                'Vicmap Planning Overlays': 'planning/overlays',
                'VLUIS Property Classification': 'vluis/property',
                'VLUIS Land Use': 'vluis/alum',
                'VLUIS Land Cover': 'vluis/landcover'
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
            alaWaterbirdSpecies: null,
            alaFrogSpecies: null,
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
      ...mapState([
        'filteredWetland',
      ]),
    },
    computed: {
        wetlandName() {
            return this.feature.get('name');
        },

      landUsePurpose(){
          let reg=/\[|]|\"|"/gm;
        return this.feature.get('land_use').replace(reg,'');
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
      threatenedAlaWaterbirdSpecies() {
        let self = this;
        let tmpArray = [];
        if (self.alaWaterbirdSpecies) {
          for (const specie of self.alaWaterbirdSpecies) {
            if (Object.keys(specie.conservation).length > 0) {
              tmpArray.push(specie);
            }
          }
          return tmpArray;
        }
      },
        threatenedAlaFrogSpecies() {
            let self = this;
            let tmpArray = [];
            if (self.alaFrogSpecies) {
              for (const specie of self.alaFrogSpecies) {
                if (Object.keys(specie.conservation).length > 0) {
                  tmpArray.push(specie);
                }
              }
              return tmpArray;
            }
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
      ...mapState([
        'filteredWetland',
      ]),
    },
    methods: {
      ...mapMutations([
        'updateFilteredWetland',
      ]),
        featureToWkt(feature, newProjection) {
            let wktOptions = {'featureProjection': 'EPSG:3857'};
            if (typeof newProjection != 'undefined') {
                wktOptions['dataProjection'] = 'EPSG:' + newProjection;
            }
            return (new WKT()).writeFeature(feature, wktOptions);
        },
        fetchAlaWaterbirds(feature) {
            let self = this;
            if (feature) {
                axios.post('/app/area/ala-birds', {
                    'wkt': self.featureToWkt(feature, 7844),
                }).then(function(response) {
                    self.alaWaterbirdSpecies = response.data;
                });
            } else {
                self.alaWaterbirdSpecies = null;
            }
        },
      openPanel(event) {
        document.getElementById(
            "aurin-sidebar").style.display = "block";
        document.getElementById(
            "map-viewport").className = "viewport";
        document.getElementById(
            "panel-open").style.display = "none";
      },
        fetchAlaFrogs(feature) {
            let self = this;
            if (feature) {
                axios.post('/app/area/ala-frogs', {
                    'wkt': self.featureToWkt(feature, 7844),
                }).then(function(response) {
                    self.alaFrogSpecies = response.data;
                });
            } else {
                self.alaFrogSpecies = null;
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
            await axios.get('/app/landuse/' + endpoint, {
                params: {
                    'feature': feature.getId().split('.').pop(),
                },
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
          if( this.filteredWetland!=feature.getId()){
            document.getElementById(
                "filter-list-dropdown").style.display = "none";
          this.updateFilteredWetland(null);
          }
            this.fetchAlaWaterbirds(feature);
            this.fetchAlaFrogs(feature);
            this.fetchLathamsSnipeSeasonalCounts(feature);
            this.fetchSnipeAlaSeasonalCounts(feature);
            this.fetchLandUsage(feature);
            this.openPanel();
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
