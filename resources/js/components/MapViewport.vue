<template>
    <div class="col-4 sidebar" id="aurin-sidebar">
    <button type="button" class="btn-close" aria-label="Close" @click="closePanel"></button>
      <button type="button" id="map-button" @click="closePanel" >View Map</button>
    <router-view name="sidebar" :key="$route.path" :feature="selectedWetland"></router-view>
  </div>
  <div class="col-8 viewport" id="map-viewport">
    <div id="viewport">
      <MapControls
          :protectionStatus="this.viewparams.wetlands.protection"
          :landUse="this.viewparams.wetlands.landuse"
          @update:protectionStatus="updateProtectionStatus"
          @update:landUse="updateLandUse"
          @reset:filters="resetMapFilters"
          :map="map"
      />
      <div id="map"></div>
    </div>
  </div>
</template>

<script>
import 'ol/ol.css';
import {Map, View} from 'ol';
import {GeoJSON} from 'ol/format';
import {transformExtent} from 'ol/proj';
import {Fill, Stroke, Style} from 'ol/style';
import TileLayer from 'ol/layer/Tile';
import VectorLayer from 'ol/layer/Vector';
import OSM from 'ol/source/OSM';
import TileWMS from 'ol/source/TileWMS';
import VectorSource from 'ol/source/Vector';

import MapControls from './MapControls.vue';
import geoserverMixin from './geoserver-mixin';
import {getNumericFeatureId, zoomToExtent} from './ol-helpers';

import {reduce, merge} from 'lodash';

import {mapActions, mapState} from 'vuex';

const selectedWetlandStyle = new Style({
  stroke: new Stroke({
    color: '#3f3f3f',
    width: 2,
  }),
  fill: new Fill({
    color: 'rgba(255,184,0,0.6)',
  }),
});

const MAP_EXTENT = [144.1168836932806130, -38.5675203851200266, 146.1634340206783236, -37.2082605301040985];

export default {
  components: {MapControls},
  data() {
    return {
      sidebar: null,
      wetlandName: '',
      layers: {
        wetlands: null,
        selected: null,
      },
      viewparams: {
        wetlands: {
          'protection': 'all',
          'landuse': 'all'
        },
      },
      map: null,
    };
  },
  computed: {
    ...mapState([
      'selectedWetland',
    ]),
  },
  mixins: [geoserverMixin],
  methods: {
/* Set the width of the sidebar to 0
and the left margin of the page content to 0 */
  closePanel(event) {
  document.getElementById(
      "aurin-sidebar").style.display = "none";
    document.getElementById(
        "map-viewport").className="map-viewport"
    document.getElementById(
        "panel-open").style.display = "block";
},
    buildViewParams(viewparams) {
      return reduce(viewparams, function(result, value, key) {
        result = (result !== '' ? result + ';' : result) + key + ':' + value;
        return result;
      }, '');
    },
    updateProtectionStatus(status) {
      this.viewparams.wetlands.protection = status;
    },
    updateLandUse(status) {
      this.viewparams.wetlands.landuse = status;
    },
    resetMapFilters() {
      this.viewparams.wetlands = {
        'protection': 'all',
        'landuse': 'all'
      }
    },
    selectFeature(e) {
      let self = this;

      const url = self.layers.wetlands.getSource().getFeatureInfoUrl(
          e.coordinate,
          self.map.getView().getResolution(),
          'EPSG:3857',
          {
            INFO_FORMAT: 'application/json',
          },
      );

      this.storeWetland(url);

    },
    renderSelectedFeature(feature) {
      let self = this;
      self.layers.selected.getSource().clear();

      if (feature) {
        self.layers.selected.getSource().addFeature(feature);
        zoomToExtent(self.map, feature.getGeometry().getExtent());
        self.pushWetlandInfoRoute(feature);
      }
    },
    pushWetlandInfoRoute(feature) {
      let featureId = getNumericFeatureId(feature);

      // Only push route if feature is different to the one in the url, otherwise assume we're loading a url from scratch
      if (featureId !== this.$route.params.id) {
        this.$router.push({name: 'wetland-report', params: {id: featureId}});
      }
    },
    pushHomeRoute() {
      this.$router.push({name: 'intro'});
    },
    ...mapActions([
      'storeWetland',
    ]),
  },
  watch: {
    selectedWetland(feature) {
      feature ? this.pushWetlandInfoRoute(feature) : this.pushHomeRoute();
      this.renderSelectedFeature(feature);
    },
    'viewparams.wetlands': {
      deep: true,
      handler: function() {
        let source = this.layers.wetlands.getSource();
        source.updateParams(
            merge(source.getParams(), {
              VIEWPARAMS: this.buildViewParams(this.viewparams.wetlands),
            }),
        );
      }
    }
  },
  mounted() {


    let self = this;

    const mwBoundary = new VectorLayer({
      source: new VectorSource({
        url: self.geoserverUrl +
            '?service=WFS&version=1.1.0&request=GetFeature&typeName=valuing_urban_wetlands:mw_boundary_simplified&outputFormat=application/json',
        format: new GeoJSON(),
      }),
      style: new Style({
        stroke: new Stroke({
          color: '#000',
          width: '1',
        }),
      }),
    });

    self.layers.wetlands = new TileLayer({
      source: new TileWMS({
        url: self.geoserverUrl,
        params: {
          LAYERS: 'valuing_urban_wetlands:wetlands',
          TILED: true,
          VIEWPARAMS: this.buildViewParams(this.viewparams.wetlands),
        },
      }),
    });

    self.layers.selected = new VectorLayer({
      source: new VectorSource({}),
      style: selectedWetlandStyle,
    });

    self.map = new Map({
      target: 'map',
      layers: [
        new TileLayer({source: new OSM()}),
        mwBoundary,
        this.layers.wetlands,
        this.layers.selected,
      ],
      view: new View({
        center: [0, 0],
        zoom: 2,
      }),
    });

    // TODO: convert map_extent to /app/config
    self.map.set('MAP_EXTENT', transformExtent(MAP_EXTENT, 'EPSG:7844', 'EPSG:3857'));
    zoomToExtent(self.map, self.map.get('MAP_EXTENT'));

    self.map.on('singleclick', self.selectFeature);
  },
};

</script>