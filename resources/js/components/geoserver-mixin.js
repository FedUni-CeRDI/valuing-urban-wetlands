export default {
    computed: {
        geoserverUrl() {
            return this.config['geoserver_base_url'] + '/geoserver/valuing_urban_wetlands/ows';
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
    },
};
