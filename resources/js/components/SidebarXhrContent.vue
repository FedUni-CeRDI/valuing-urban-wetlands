<template>
    <div v-html="content"></div>
</template>

<script>
export default {
    name: 'SidebarXhrContent',
    data() {
        return {
            content: 'Loading ...',
        };
    },
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
            });
        }
    },

};

</script>

<style scoped>

</style>
