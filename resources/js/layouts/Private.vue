<template>

	<b-container>
		<TopBar class="mb-4" />
		<slot />
	</b-container>

</template>

<script>

	import TopBar from "../components/TopBar.vue";

	export default {
		name: "private",
		components: {
            TopBar,
        }
	}

</script>

<style scoped>

</style>
