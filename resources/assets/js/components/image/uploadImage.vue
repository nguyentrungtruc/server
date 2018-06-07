<template>
	<div>
		{{avatar}}
		<a @click.stop.prevent="onPickImage" style="width:inherit;height:inherit;border-radius:inherit"><img :src="imageUrl != null ? imageUrl : 'https://www.dofuu.com/img/default.png'" alt="avatar" style="width:285px; height:160px; cursor:pointer"></a>
		<input type="file" style="display:none" ref="fileInput" accept="image/*" v-validate="'mimes:image/jpeg, image/png, image/jpg'" @change="onImagePicked">
	</div>
</template>

<script>
import image from '@/mixins/imageUrl'
import {imageURL} from '@/config/config'
export default {
	mixins: [image],
	props: ['avatar'],
	data() {
		return {
			imageUrl: null
		}
	},
	methods: {
		//Input pick image
		onPickImage: function(e) {
			this.$refs.fileInput.click();
		},
		//Transfer and resize Image
		onImagePicked: function(event) {
			let vm = this
			const file = event.target.files || event.dataTransfer.files
			let filename = file[0].name

			if(!file.length) {
				return 
			} 

			const fileReader = new FileReader()
			fileReader.onload = (e) => {
				var image = new Image()
				image.src = fileReader.result
				image.onload = (event) => {
					const MAX_WIDTH = 720
					const MAX_HEIGHT = 480
					let tempW = image.width
					let tempH = image.height 
					if(tempW > tempH) {
						if(tempW > MAX_WIDTH) {
							tempH *= MAX_WIDTH / tempW;
							tempW =MAX_WIDTH
						}
					} else {
						if(tempH > MAX_HEIGHT) {
							tempW *= MAX_HEIGHT / tempH
							tempH = MAX_HEIGHT
						}
					}
					var canvas = document.createElement('canvas');
					canvas.width = tempW
					canvas.height = tempH
					canvas.getContext("2d").drawImage(image, 0, 0, tempW, tempH)
					var dataURL = canvas.toDataURL("image/jpeg")
					this.imageUrl = dataURL
					this.$emit('IMAGE', dataURL)
				}				
			}
			fileReader.readAsDataURL(file[0])
		}
	},
	watch: {
		avatar(val) {
			console.log(val)
			if(val == null) {

				this.imageUrl =  imageURL + '/img/default.png'

			} else {
				if(val.lastIndexOf('data')>-1) {

					this.imageUrl = val

				} else {

					this.imageUrl = imageURL + val
				}
			}
		}
	}
}
</script>

<style>


</style>