<template>
    <div class="map-controls">
        <form>
            <div class="row justify-content-center align-items-center">
                <div class="col-auto">
                    <button class="btn btn-sm btn-light" type="button" @click="zoomToFullExtent(map)">
                        <i class="bi bi-arrows-angle-expand"></i>
                    </button>
                </div>
                <div class="col-auto">
                    <WetlandSearch/>
                </div>
                <div class="col-auto">
                    <div class="form-floating">
                        <select class="form-select"
                                aria-label="Filter by protection status"
                                name="protection_status"
                                id="protectionStatus"
                                :value="protectionStatus"
                                @input="$emit('update:protectionStatus', $event.target.value)"
                        >
                            <option value="National Park">National Park</option>
                            <option value="Reference Area">Reference Area</option>
                            <option value="Conservation Park">Conservation Park</option>
                            <option value="State Park">State Park</option>
                            <option value="Natural Features Reserve">Natural Features Reserve</option>
                            <option value="Other">Other</option>
                            <option value="Private Nature Reserve">Private Nature Reserve</option>
                            <option value="Nature Conservation Reserve">Nature Conservation Reserve</option>
                            <option value="Heritage River">Heritage River</option>
                            <option value="Natural Catchment Area">Natural Catchment Area</option>
                            <option value="None">None</option>
                            <option value="">No Filter</option>
                        </select>
                        <label for="protectionStatus">Filter by protection status</label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import WetlandSearch from "@/components/WetlandSearch.vue";
import {zoomToFullExtent} from "@/components/ol-helpers";

export default {
    methods: {
        zoomToFullExtent(map) {
            return zoomToFullExtent(map)
        }
    },
    components: {WetlandSearch},
    props: ['protectionStatus', 'map'],
    emits: ['update:protectionStatus'],
    mounted() {

    },
};

</script>
