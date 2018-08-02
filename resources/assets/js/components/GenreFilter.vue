<template>
	<div class="form-group">
		<label for="genres">Genre</label>
		<select multiple id="genres" class="form-control" name="genres[]" v-model="oldGenres">
			<option v-for="genre in genres" v-bind:key="genre.id" :value="genre.id">{{ genre.name }}</option>
		</select>
	</div>
</template>

<script>
	export default {
		props: {
			oldGenres: Array
		},
		data () {
			return {
				genres: []
			}
		},
		mounted () {
			axios.get('https://api.themoviedb.org/3/genre/movie/list?api_key=3e910cad37d9074e8158d711279976a0&language=en-US')
			.then(response => {
				this.genres = response.data.genres
			})
			.catch(e => {
				this.errors.push(e)
			})
		},
		
	}
</script>