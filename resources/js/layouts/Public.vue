<template>

	<b-container class="pt-5">
        <b-row>
            <b-col class="text-center mt-5 mb-5">
              <img :src="logo" width="150" />
            </b-col>
        </b-row>

		<slot />
		
	</b-container>

</template>

<script>

	export default {
		data() {
			return {
			logo: require("../assets/icons/logo.png"),
			};
		}
	};

</script>

<style scoped>


</style>