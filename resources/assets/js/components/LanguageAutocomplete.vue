<template>
	<div>
		<v-autocomplete :items="languages" v-model="language" :get-label="getLabel" :component-item='template' @item-selected="itemSelected" @update-items="updateItems" :auto-select-one-item="false" :min-len='0' :input-attrs="{'placeholder': 'Enter language'}">
		</v-autocomplete>
		<input type="hidden" name="languageCode" v-model.lazy="languageCode">
	</div>
</template>

<script>
import ItemTemplate from './ItemTemplate.vue'
import Languages from './languages.js'

export default {
	props: {
		oldLanguageCode: String
	},
  	data () {
		return {
			language: Languages.find((item) => {
				return this.oldLanguageCode === item.iso_639_1
			}),
			languageCode: "",
	  		languages: [
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
		getLabel (language) {
			if (language) {
				return language.english_name
			}
			
			return ''
		},
		itemSelected (language) {
			this.languageCode = language.iso_639_1
		},
		updateItems (text) {
			this.languages = Languages.filter((language) => {
				return (new RegExp(text.toLowerCase())).test(language.english_name.toLowerCase() + language.name.toLowerCase())
			})
		}
	}
}
</script>

<style lang="css">
.v-autocomplete-input {
  width: 100%;
}

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
