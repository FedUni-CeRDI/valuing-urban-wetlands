export function zoomToExtent(map, extent) {

    map.getView().fit(extent, {
        padding: [50, 50, 50, 50],
    });
}

export function getNumericFeatureId(feature) {
    return feature.getId().split('.')[1];
}
