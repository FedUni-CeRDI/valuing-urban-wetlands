import {transformExtent} from "ol/proj";

export function zoomToFullExtent(map) {
    zoomToExtent(map,[144.1168836932806130, -38.5675203851200266, 146.1634340206783236, -37.2082605301040985]);
}

export function zoomToExtent(map, extent) {
    extent = transformExtent(extent, 'EPSG:4283', 'EPSG:3857');

    map.getView().fit(extent, {
        padding: [50, 50, 50, 50],
    });
}

