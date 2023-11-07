<template>
    <div v-html="content"></div>
</template>

<script>
import {Popover} from 'bootstrap';

export default {
    name: 'SidebarXhrContent',
    data() {
        return {
            content: 'Loading ...',
        };
    },
    watch: {},
    computed: {
        contentPath() {
            return this.$route.name ? '/content/' + this.$route.name : null;
        },
    },
    mounted() {
        const self = this;
        if (self.contentPath) {
            axios.get(self.contentPath).then(function(response) {
                self.content = response.data;
            }).then(function() {

                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new Popover(tooltipTriggerEl);
                });

            });
        }
    },

};

</script>

<style scoped>

</style>
