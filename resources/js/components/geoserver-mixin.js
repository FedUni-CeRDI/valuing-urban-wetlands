import {mapActions} from 'vuex';

export default {
    computed: {
        geoserverUrl() {
            return this.config['geoserver_base_url'] + '/geoserver/aurin/ows';
        },
    },
    methods: {
        getWfsFeatureInfo: function(workspace, layer, featureId) {
            const params = new URLSearchParams({
                'service': 'WFS',
                'version': '2.0.0',
                'featureId': featureId,
                'request': 'GetFeature',
                'typeNames': workspace + ':' + layer,
                'outputFormat': 'application/json',
            });
            return this.geoserverUrl + '?' + params.toString();
        },
        geoJsonCallback: function(response) {
            let geoJsonFeature = null;
            if (response.data.features.length) {
                geoJsonFeature = response.data.features[0];
            }
            return geoJsonFeature;
        }
    },


};
