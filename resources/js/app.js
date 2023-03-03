import './bootstrap';

import 'ol/ol.css';
import {View, Map} from 'ol';
import OSM from 'ol/Source/OSM';
import TileLayer from 'ol/layer/Tile';

import {Projection} from 'ol/proj';
import VectorLayer from 'ol/layer/Vector';
import VectorSource from 'ol/source/Vector';
import {Stroke, Style} from 'ol/style';
import {GeoJSON} from 'ol/format';


const vectorLayer = new VectorLayer({
    source: new VectorSource({
        url: '/storage/mw_boundary_simplified.geojson',
        format: new GeoJSON(),
    }),
    style: function(feature) {
        return new Style({
            stroke: new Stroke({
                color: '#ffe887',
                width: '4'
            })
        })
    }
})


const map = new Map({
    target: 'map',
    layers: [
        new TileLayer({
            source: new OSM()
        }),
        vectorLayer
    ],
    view: new View({
        center: [0, 0],
        zoom: 2,
        projection: new Projection({
            code: 'EPSG:4326',
            units: 'm'
        })
    })
})

map.getView().fit([144.1168836932806130,-38.5675203851200266, 146.1634340206783236,-37.2082605301040985], {
    padding: [20, 20, 20, 20]
});
