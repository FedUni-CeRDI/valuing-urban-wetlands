import './bootstrap';

import {createApp} from 'vue/dist/vue.esm-bundler';

import proj4 from 'proj4';
import {register} from 'ol/proj/proj4';

import {createRouter, createWebHashHistory} from 'vue-router';
import {createStore} from 'vuex';

proj4.defs('EPSG:7844', '+proj=longlat +ellps=GRS80 +no_defs +type=crs');
register(proj4);

import MapViewport from './components/MapViewport.vue';
import SidebarXhrContent from './components/SidebarXhrContent.vue';
import WetlandReport from './components/WetlandReport.vue';
import {GeoJSON} from 'ol/format';

const router = createRouter({
    history: createWebHashHistory(),
    routes: [
        {path: '/', name: 'intro', components: {sidebar: SidebarXhrContent}},
        {path: '/about', name: 'about', components: {'sidebar': SidebarXhrContent}},
        {path: '/terms', name: 'terms', components: {'sidebar': SidebarXhrContent}},
        {path: '/contact', name: 'contact', components: {'sidebar': SidebarXhrContent}},
        {path: '/wetland/:id', name: 'wetland-report', components: {'sidebar': WetlandReport}, props: true},
    ],
});

const store = createStore({
    state() {
        return {
            selectedWetland: null,
            speciesInfo: null,
            wetlandNames: [],
        };
    },
    mutations: {
        storeWetlandNames(state, names) {
            state.wetlandNames = names;
        },
        selectWetland(state, geoJson) {
            let feature = null;

            if (geoJson !== null) {
                // parse stringified JSON properties
                let jsonFields = [
                    'protection_status',
                    'states',
                ];
                jsonFields.forEach(function(field) {
                    geoJson.properties[field] = JSON.parse(
                        geoJson.properties[field]);
                });
                feature = (new GeoJSON()).readFeature(geoJson);
            }

            state.selectedWetland = feature;
        },

        updateSpeciesInfo(state, speciesInfo) {
            state.speciesInfo = speciesInfo;
        },
    },
    actions: {
        async storeWetland(state, url) {

            let response = await axios.get(url).then(function(response) {
                let geoJsonFeature = null;
                if (response.data.features.length) {
                    geoJsonFeature = response.data.features[0];
                }
                return geoJsonFeature;
            });

            state.commit('selectWetland', response);
        },
    },
});

axios.get('/app/wetlands').then(
    function(response) {
        store.commit('storeWetlandNames', response.data);
    },
);

axios.get('/app/species-info').then(function(response) {
    store.commit('updateSpeciesInfo', response.data);
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
