<template>
	<div class="form-group">
		<label for="genres">Genre</label>
		<select multiple id="genres" class="form-control" name="genres[]" v-model="selectedGenres">
			<option v-for="genre in genres" v-bind:key="genre.id" :value="genre.id">{{ genre.name }}</option>
		</select>
	</div>
</template>

<script>
	export default {
		props: {
			oldGenres: Array,
			route: String
		},
		data () {
			return {
				selectedGenres: this.oldGenres ? this.oldGenres : [],
				genres: []
			}
		},
		mounted () {
			axios.get('https://api.themoviedb.org/3/genre/' + this.route + '/list?api_key=3e910cad37d9074e8158d711279976a0&language=en-US')
			.then(response => {
				this.genres = response.data.genres
			})
			.catch(e => {
				this.errors.push(e)
			})
		},
		
	}
</script>