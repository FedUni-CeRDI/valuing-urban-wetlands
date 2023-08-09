<template>
    <div class="col-4 sidebar ">
        <router-view name="sidebar" :key="$route.path" ></router-view>
    </div>
    <div class="col-8 viewport">
        <div id="viewport">
            <MapControls
                :protectionStatus="protectionStatus"
                @update:protectionStatus="status => protectionStatus = status"
                :map="map"
            />
            <div id="map"></div>
        </div>
    </div>

</template>

<script>
import 'ol/ol.css';
import {Feature, Map, View} from 'ol';
import OSM from 'ol/Source/OSM';
import TileLayer from 'ol/layer/Tile';

import {transformExtent} from 'ol/proj';
import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';
import {Fill, Stroke, Style} from 'ol/style';
import {GeoJSON, MVT, WKT} from 'ol/format';
import MapControls from '@/components/MapControls.vue';
import VectorTileSource from 'ol/source/VectorTile';
import VectorTileLayer from 'ol/layer/VectorTile';
import {zoomToExtent, zoomToFullExtent} from "@/components/ol-helpers";

const wetlandStyle = new Style({
    stroke: new Stroke({
        color: '#3F3F3F',
        width: 1,
    }),
    fill: new Fill({
        color: 'rgba(99,183,0,0.45)',
    }),
});
const selectedWetlandStyle = new Style({
    stroke: new Stroke({
        color: '#3F3F3F',
        width: 1,
    }),
    fill: new Fill({
        color: 'rgba(255,180,0,0.45)',
    }),
});

const hiddenStyle = new Style({
    stroke: new Stroke({
        color: 'rgba(255,255,255,0)',
        width: 0,
    }),
    fill: new Fill({
        color: 'rgba(255,255,255,0)',
    }),
});

export default {
    components: {MapControls},
    data() {
        return {
            sidebar: null,
            protectionStatus: '',
            wetlandName: '',
            layers: {
                wetlands: null,
            },
            map: null
        };
    },
    computed: {
        selectedWetland() {
            return this.$store.state.selectedWetland;
        }
    },
    methods: {
        visibleFeatureStyle(feature) {
            return this.selectedWetland !== null && this.selectedWetland.getId() === feature.getId() ? selectedWetlandStyle : wetlandStyle;
        },
        selectFeature(e) {
            let self = this;

            this.layers.wetlands.getFeatures(e.pixel).then(function(features) {
                if (!features.length) {
                    self.$store.commit('deselectWetland');
                    return;
                }

                let feature = features[0];

                // Select feature
                self.$store.commit('selectWetland', feature);
            });

            // Trigger redraw
         //   this.layers.wetlands.changed();
        },
        renderSelectedFeature(feature) {
            let self = this;

            // Zoom To Selected Feature
            // VectorTiles splits geometries across tiles and so doesn't have a concept of a complete geometry
            // This means normal feature.getGeometry().getExtent() changes based on map zoom/resolution
            // Therefore we send the geometry's extent through from Geoserver/PostGIS and use that to zoom to feature
            if (feature.get('extent')) {
                zoomToExtent(self.map, JSON.parse(feature.get('extent')));
            }

            self.displayFeatureInfo(feature);
        },
        displayFeatureInfo(feature) {
            this.$router.push({name: 'wetland-report', params: { id: feature.getId() }})
        },
        // zoomToExtent(extent) {
        //     extent = transformExtent(extent, 'EPSG:4283', 'EPSG:3857');
        //
        //     this.map.getView().fit(extent, {
        //         padding: [50, 50, 50, 50],
        //     });
        // },
        wetlandStyler(feature) {
            let self = this;
            if (self.protectionStatus) {
                let capadStatus = JSON.parse(feature.getProperties()['capad_status']);
                capadStatus = capadStatus.map(status => status === null ? 'None' : status);

                if (capadStatus.indexOf(self.protectionStatus) > -1) {
                    return self.visibleFeatureStyle(feature);
                }
                return hiddenStyle;
            }
            return self.visibleFeatureStyle(feature);
        },
        // zoomToFullExtent() {
        //     this.zoomToExtent([144.1168836932806130, -38.5675203851200266, 146.1634340206783236, -37.2082605301040985]);
        // }
    },
    watch: {
        protectionStatus(newStatus, oldStatus) {
            // trigger Style function
            this.layers.wetlands.changed();
        },
        selectedWetland(feature) {
            if (feature) {
                this.renderSelectedFeature(feature)
            }

            // Trigger redraw
            this.layers.wetlands.changed();
        }
    },
    mounted() {
        let self = this;

        const mwBoundary = new VectorLayer({
            source: new VectorSource({
                url: self.config['geoserver_base_url'] +
                    '/geoserver/aurin/wfs?service=WFS&version=1.1.0&request=GetFeature&typeName=aurin:mw_boundary_simplified&outputFormat=application/json',
                format: new GeoJSON(),
            }),
            style: new Style({
                stroke: new Stroke({
                    color: '#000',
                    width: '1',
                }),
            }),
        });

        self.layers.wetlands = new VectorTileLayer({
            declutter: true,
            source: new VectorTileSource({
                format: new MVT({
                    idProperty: 'id',
                    featureClass: Feature,
                }),
                url: self.config['geoserver_base_url'] +
                    '/geoserver/gwc/service/tms/1.0.0/' +
                    'aurin:wetlands@EPSG%3A900913@pbf/{z}/{x}/{-y}.pbf',
            }),
            style: self.wetlandStyler,
        });

        self.map = new Map({
            target: 'map',
            layers: [
                new TileLayer({source: new OSM()}),
                mwBoundary,
                this.layers.wetlands,
            ],
            view: new View({
                center: [0, 0],
                zoom: 2,
            }),
        });

        zoomToFullExtent(self.map),
        self.map.once('rendercomplete', () => {
            self.$store.commit('storeWetlands', self.layers.wetlands)
        })

        self.map.on('singleclick', self.selectFeature);


    },
}
;

</script>
