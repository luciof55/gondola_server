<template>
    <div></div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted??????.')
        }
    };
	
	var vm = new Vue({
		data: function () {
			return {
			  count: 0
			}
		},
		template: '<button v-on:click="count++">You clicked me {{ count }} times.</button>'
	})
</script>


