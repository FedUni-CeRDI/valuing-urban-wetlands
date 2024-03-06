<template>

    <div class="row justify-content-center align-items-center map-controls">
        <div class="col-auto">
            <button class="btn btn-sm btn-light d-block mt-3" title="View full map" type="button"
                    @click="zoomToFullExtent(map)">
                <i class="bi bi-zoom-out" title="View full map"></i>
            </button>
            <button class="btn btn-sm btn-light d-block" title="Reset map filters" type="button"
                    @click="resetFilters(map)">
                <i class="bi bi-eraser" title="Reset map filters"></i>
            </button>
            <a href="#" id="panel-open"  @click="openNav">
              <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>


    <div class="row justify-content-center align-items-center map-filters">
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
                        aria-label="Filter by land use"
                        name="landuse"
                        id="landUse"
                        :value="landUse"
                        @input="$emit('update:landUse', $event.target.value)"
                >
                    <option value="Channel/aqueduct">Channel/aqueduct</option>
                    <option value="Cropping">Cropping</option>
                    <option value="Estuary/coastal waters">Estuary/coastal waters</option>
                    <option value="Grazing irrigated modified pastures">Grazing irrigated modified
                        pastures
                    </option>
                    <option value="Grazing modified pastures">Grazing modified pastures</option>
                    <option value="Grazing native vegetation">Grazing native vegetation</option>
                    <option value="Intensive animal production">Intensive animal production</option>
                    <option value="Intensive horticulture">Intensive horticulture</option>
                    <option value="Irrigated cropping">Irrigated cropping</option>
                    <option value="Irrigated land in transition">Irrigated land in transition</option>
                    <option value="Irrigated perennial horticulture">Irrigated perennial horticulture
                    </option>
                    <option value="Irrigated plantation forests">Irrigated plantation forests</option>
                    <option value="Irrigated seasonal horticulture">Irrigated seasonal horticulture
                    </option>
                    <option value="Lake">Lake</option>
                    <option value="Land in transition">Land in transition</option>
                    <option value="Managed resource protection">Managed resource protection</option>
                    <option value="Manufacturing and industrial">Manufacturing and industrial</option>
                    <option value="Marsh/wetland">Marsh/wetland</option>
                    <option value="Mining">Mining</option>
                    <option value="Nature conservation">Nature conservation</option>
                    <option value="Other minimal use">Other minimal use</option>
                    <option value="Perennial horticulture">Perennial horticulture</option>
                    <option value="Plantation forests">Plantation forests</option>
                    <option value="Production native forests">Production native forests</option>
                    <option value="Reservoir/dam">Reservoir/dam</option>
                    <option value="Residential and farm infrastructure">Residential and farm infrastructure</option>
                    <option value="River">River</option>
                    <option value="Seasonal horticulture">Seasonal horticulture</option>
                    <option value="Services">Services</option>
                    <option value="Transport and communication">Transport and communication</option>
                    <option value="Utilities">Utilities</option>
                    <option value="Waste treatment and disposal">Waste treatment and disposal</option>
                    <option value="all">No Filter</option>
                </select>
                <label for="landUse">Filter by land use</label>
            </div>
        </div>
        <div class="col-12 col-lg-7 offset-lg-5 col-xl-6 offset-xl-6 mb-1">
            <WetlandSearch/>
        </div>
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
        resetFilters() {
            this.$emit('reset:filters');
        },
      openNav(event) {
        document.getElementById(
            "aurin-sidebar").style.display = "block";
        document.getElementById(
            "map-viewport").className = "viewport";
        document.getElementById(
            "panel-open").style.display = "none";
      },
    },
    components: {WetlandSearch},
    props: ['protectionStatus', 'landUse', 'map'],
    emits: ['update:protectionStatus', 'update:landUse', 'reset:filters'],
    mounted() {

    },
};

</script>
