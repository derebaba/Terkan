<template>
  	<v-autocomplete :items="items" v-model="item" :get-label="getLabel" :component-item='template' @update-items="updateItems" :auto-select-one-item="false" :min-len='0'>
  	</v-autocomplete>
</template>

<script>
import ItemTemplate from './ItemTemplate.vue'
import Languages from './languages.js'

export default {
  	data () {
		return {
	  		item: {
				"iso_639_1": "en",
				"english_name": "English",
				"name": "English"
			},
	  		items: [
				{
					"iso_639_1": "tr",
					"english_name": "Turkish",
					"name": "Türkçe"
				},
				{
					"iso_639_1": "en",
					"english_name": "English",
					"name": "English"
				},
			],
			template: ItemTemplate,
		}
	},
	methods: {
		getLabel (item) {
			if (item) {
				return item.english_name
			}

			return ''
		},
		updateItems (text) {
			this.items = Languages.filter((item) => {
				return (new RegExp(text.toLowerCase())).test(item.english_name.toLowerCase())
			})
		}
	}
}
</script>

<style lang="css">
.v-autocomplete-list {
  width: 100%;
  max-height: 200px;
  overflow: auto;
  z-index: 9999;
  border: 1px solid #eeeeee;
  border-radius: 4px;
  background-color: #fff;
  box-shadow: 0px 1px 6px 1px rgba(0, 0, 0, 0.4);
}

.v-autocomplete-list-item {
  font-weight: normal;
  color: #333333;
}

.v-autocomplete-item-active {
	color: #23527c;
  	background-color: #eeeeee;
}
</style>
