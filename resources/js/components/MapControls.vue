<template>
    <div class="map-controls">
        <form>
            <div class="row">
                <div class="col-auto">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-auto">
                            <button class="btn btn-sm btn-light" type="button" @click="zoomToFullExtent(map)">
                                <i class="bi bi-arrows-angle-expand"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row justify-content-center align-items-center">

                        <div class="col-12 col-lg-7 offset-lg-5 col-xl-6 offset-xl-6 mb-1">
                            <div class="form-floating">
                                <select class="form-select"
                                        aria-label="Filter by protection status"
                                        name="protection_status"
                                        id="protectionStatus"
                                        :value="protectionStatus"
                                        @input="$emit('update:protectionStatus', $event.target.value)"
                                >
                                    <option value="Conservation Park">Conservation Park</option>
                                    <option value="Heritage River">Heritage River</option>
                                    <option value="Natural Catchment Area">Natural Catchment Area</option>
                                    <option value="Nature Conservation Reserve">Nature Conservation Reserve</option>
                                    <option value="Natural Features Reserve">Natural Features Reserve</option>
                                    <option value="National Park">National Park</option>
                                    <option value="Private Nature Reserve">Private Nature Reserve</option>
                                    <option value="Ramsar">Ramsar Site</option>
                                    <option value="Reference Area">Reference Area</option>
                                    <option value="State Park">State Park</option>
                                    <option value="Other">Other</option>
                                    <option value="none">No protection</option>
                                    <option value="any">Any protection</option>
                                    <option value="all">No Filter</option>
                                </select>
                                <label for="protectionStatus">Filter by protection status</label>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7 offset-lg-5 col-xl-6 offset-xl-6 mb-1">
                            <div class="form-floating">
                                <select class="form-select"
                                        aria-label="Filter by catchment land use"
                                        name="landuse"
                                        id="landUse"
                                        :value="landUse"
                                        @input="$emit('update:landUse', $event.target.value)"
                                >
                                    <option value="Dryland cropping">Dryland cropping</option>
                                    <option value="Dryland horticulture">Dryland horticulture</option>
                                    <option value="Grazing modified pastures">Grazing modified pastures</option>
                                    <option value="Grazing native vegetation">Grazing native vegetation</option>
                                    <option value="Intensive animal and plant production">Intensive animal and plant
                                        production
                                    </option>
                                    <option value="Irrigated cropping">Irrigated cropping</option>
                                    <option value="Irrigated horticulture">Irrigated horticulture</option>
                                    <option value="Irrigated pastures">Irrigated pastures</option>
                                    <option value="Mining and waste">Mining and waste</option>
                                    <option value="Nature conservation">Nature conservation</option>
                                    <option value="Other minimal use">Other minimal use</option>
                                    <option value="Plantation forestry">Plantation forestry</option>
                                    <option value="Production forestry">Production forestry</option>
                                    <option value="Rural residential and farm infrastructure">Rural residential and farm
                                        infrastructure
                                    </option>
                                    <option value="Urban intensive uses">Urban intensive uses</option>
                                    <option value="Water">Water</option>
                                    <option value="No data">No data</option>
                                    <option value="all">No Filter</option>
                                </select>
                                <label for="landUse">Filter by catchment land use</label>
                            </div>
                        </div>
                        <div class="col-12 col-lg-7 offset-lg-5 col-xl-6 offset-xl-6 mb-1">
                            <WetlandSearch/>
                        </div>
                    </div>
                </div>
            </div>


        </form>
    </div>
</template>

<script>
import WetlandSearch from './WetlandSearch.vue';
import {zoomToExtent} from './ol-helpers';

export default {
    methods: {
        zoomToFullExtent(map) {
            return zoomToExtent(map, map.get('MAP_EXTENT'));
        },
    },
    components: {WetlandSearch},
    props: ['protectionStatus', 'landUse', 'map'],
    emits: ['update:protectionStatus', 'update:landUse'],
    mounted() {

    },
};

</script>
