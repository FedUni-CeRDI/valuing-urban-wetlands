<script>
import {Modal} from 'bootstrap';
import {isArray} from 'lodash';

export default {
    name: 'SpeciesList',
    emits: [
        'closed',
    ],
    props: {
        visible: Boolean,
        speciesList: Array,
        title: String,
        id: String,
    },
    data() {
        return {
            modal: null,
        };
    },
    watch: {
        'visible': function() {
            this.toggleModal();
        },
    },
    methods: {
        toggleModal() {
            this.visible ? this.modal.show() : this.modal.hide();
        },
        isThreatened(specie) {
            return !isArray(specie.conservation) ? 'Yes' : 'No';
        },
        commonNames(specie) {
            let commonNames;
            if (specie.hasOwnProperty('common_names')) {
                commonNames = specie.common_names.join(', ');
            } else {
                commonNames = specie.common_name;
            }
            return commonNames;
        },
    },
    mounted() {
        const vm = this;
        let container = document.getElementById(this.id);
        this.modal = new Modal(container);
        container.addEventListener('hide.bs.modal', function(e) {
            vm.$emit('closed');
        });
        this.toggleModal();
    },
};
</script>

<template>
    <div>
        <div :id="id" class="modal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Scientific Name</th>
                                <th>Common names</th>
                                <th>Threatened?</th>
                            </tr>
                            <tr v-for="(specie) in speciesList">
                                <td>
                                    <a :href="'https://bie.ala.org.au/species/' + specie.guid"
                                       target="_blank">{{ specie.scientific_name }}</a> <a
                                    :href="'https://bie.ala.org.au/species/' + specie.guid" target="_blank"><i
                                    class="bi bi-box-arrow-up-right"></i></a>
                                </td>
                                <td class="species-column">{{ commonNames(specie) }}</td>
                                <td>{{ isThreatened(specie) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.table-bordered {
    th, td {
        padding: 0.3em 0.7em;
    }

    td > a {
        padding: 0;

        i {
            text-decoration: none;
        }
    }
}

.modal-body {
    padding: 0;
}
</style>
