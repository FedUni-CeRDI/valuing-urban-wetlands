import './bootstrap';

import {createApp} from 'vue/dist/vue.esm-bundler';

import proj4 from 'proj4';
import {register} from 'ol/proj/proj4';

import {createRouter, createWebHashHistory} from 'vue-router';
import { createStore } from 'vuex';

import _ from 'lodash';

proj4.defs('EPSG:4283', '+proj=longlat +ellps=GRS80 +no_defs +type=crs');
proj4.defs("EPSG:7844", "+proj=longlat +ellps=GRS80 +no_defs +type=crs");
proj4.defs("EPSG:4326", "+proj=longlat +datum=WGS84 +no_defs +type=crs");
register(proj4);

import MapViewport from './components/MapViewport.vue';
import SidebarXhrContent from './components/SidebarXhrContent.vue';
import WetlandReport from './components/WetlandReport.vue';

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        { path: '/', name: 'intro', components: {sidebar: SidebarXhrContent }},
        { path: '/about', name: 'about', components: { 'sidebar': SidebarXhrContent }},
        { path: '/terms', name: 'terms', components: { 'sidebar': SidebarXhrContent }},
        { path: '/contact', name: 'contact', components: { 'sidebar': SidebarXhrContent }},
        { path: '/wetland/:id', name: 'wetland-report', components: { 'sidebar': WetlandReport }}
    ],
});

const store = createStore({
    state() {
        return {
            selectedWetland: null,
            speciesInfo: null,
            threatenedSpeciesInfo: null,
            wetlands: null
        }
    },
    mutations: {
        storeWetlands(state, wetlands) {
            state.wetlands = wetlands.getSource().getFeaturesInExtent(
                wetlands.getSource().getTileGrid().getExtent()
            )
        },
        selectWetland(state, feature) {
            state.selectedWetland = feature;
        },
        deselectWetland(state) {
            console.log('deselecte')
            state.selectedWetland = null;
        },
        updateSpeciesInfo(state, speciesInfo) {
            state.speciesInfo = speciesInfo;
            state.nationalThreatenedSpeciesInfo = _.filter(speciesInfo, 'conservation_aus');
            state.vicThreatenedSpeciesInfo = _.filter(speciesInfo, 'conservation_vic');
        }
    },
    getters: {
        featureProperties(state) {
            let feature = state.selectedWetland;
            if (feature) {
                let properties = feature.getProperties();
                properties.capad_status = JSON.parse(properties.capad_status);
                properties.geojson = JSON.parse(properties.geojson);
                return properties;
            }
            return null;
        },
        wetlandFeatures(state) {
            return state.wetlands;
        }
    }
});

axios.get('/app/species-info').then(function(response) {
    store.commit('updateSpeciesInfo', response.data)
});

const app = createApp({
    components: {
        'map-viewport': MapViewport,
    },
});

app.config.globalProperties.config = await axios.get('/app/config').
    then(function(response) {
        return response.data;
    });



app.use(store);
app.use(router);
app.mount('#app');
