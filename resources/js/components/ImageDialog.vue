<template>
	<v-dialog v-model="dialog" persistent max-width="800px" :fullscreen="$vuetify.breakpoint.smAndDown">
		<v-card>
			<v-toolbar dense>
				<span class="headline">{{title}}</span>
			</v-toolbar>
			<v-divider></v-divider>
			<v-layout row wrap class="justify-center pt-3">			
				<vue-croppie 
                                                    ref           = "croppieRef"
                                                    @result       = "result"
                                                  :enableResize   = "resize"
                                                  :enableExif     = "true"
                                                  :mouseWheelZoom = "true"
                                                    @update       = "update"
                                                  :viewport       = "viewport"
                                                  :boundary       = "boundary"
                >
				</vue-croppie>
				<v-btn @click.stop.prevent="onPickImage" :loading="loading" rounded small><v-icon left >camera_alt</v-icon> Tải ảnh</v-btn>
				<v-btn small class="elevation-1" icon @click="rotate(90)"><v-icon>rotate_left</v-icon></v-btn>
				<v-btn small class="elevation-1" icon @click="rotate(-90)"><v-icon>rotate_right</v-icon></v-btn>
				<input type="file" style="display:none" ref="fileInput" accept="image/*" v-validate="'mimes:image/jpeg, image/png, image/jpg'" @change="onImagePicked">
			</v-layout>		
			<v-divider></v-divider>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn class="ma-2" @click.native="cancel" small rounded>Cancel</v-btn>
				<v-btn class="ma-2" color="blue" rounded @click.native="agree" small :disabled="disabled" :loading="process">Update</v-btn>
			</v-card-actions>
		</v-card>
		<d-confirm ref="confirm"></d-confirm>
	</v-dialog>
</template>
<script>
	import {mapState} from 'vuex'
	import index from '@/mixins/index'
	import {Confirm} from './index'
	export default {
		mixins    : [index],
		components: {
			'd-confirm': Confirm
		},
		data() {
			return {
				imageUrl: null,
				cropped : null,
				dialog  : false,
				width   : 0,
				height  : 0,
				title   : null,
				resolve : null,
				reject  : null,
				viewport: {
					width : 250,
					height: 250
				},
				size: {
					width : 350,
					height: 350
				},
				boundary: {
					width : 350,
					height: 350
                },
                resize  : true,
                loading : false,
                process : false,
                disabled: true
			}
		},
		methods: {
			open(title, size) {
                this.dialog  = true
                this.title   = title
                this.size    = size
                this.loading = false
				return new Promise((resolve, reject) => {
					this.resolve = resolve
					this.reject  = reject
				})
			},
			cancel() {
				var vm = this
				if(!!vm.cropped) {
					vm.$refs.confirm.open('Confirm', 'The changes you make to your avatar will not be saved if you close the dialog box.').then(async(confirm) => {
						if(confirm) {
							vm.cropped = await null
							await vm.refresh()			
							vm.resolve({status: false})
							vm.dialog = false
						}
					})
				} else {
					vm.resolve({status: false})
					vm.dialog = false
				}
			},
			agree() {
				var vm = this
				if(!vm.process) {
					vm.process = !vm.process
					setTimeout(async ()=> {
						vm.resolve({status: true, avatar: vm.cropped})
						vm.process = !vm.process
						vm.cropped = await null
						await vm.refresh()
						vm.dialog = false
					}, 200)					
				}				
			},
			result(output) {				

			},
			update(val) {
				let options = {
					format: 'jpeg',
					size  : this.$refs.croppieRef.croppie.options.boundary,
					circle: false
				}
				this.$refs.croppieRef.result(options, (output) => {
					this.cropped = output;
				});
			},
			// Rotates the image
			rotate(rotationAngle) {
				this.$refs.croppieRef.rotate(rotationAngle);
			},
			//Input pick image
			onPickImage: function(e) {
				this.$refs.fileInput.click();
			},
			//Transfer and resize Image
			onImagePicked: function(event) {
				let   vm   = this
				const file = event.target.files || event.dataTransfer.files
				if(!file.length) {
					return 
				} 
				let   filename   = file[0].name
				      vm.loading = !vm.loading
				const fileReader = new FileReader()

				fileReader.onload = (e) => {
					var image     = new Image()
					    image.src = fileReader.result

					setTimeout(() => {
						vm.loading = !vm.loading
						this.$refs.croppieRef.bind({
							url: image.src,
						});			
					}, 1000)
				}
				fileReader.readAsDataURL(file[0])
			},
			refresh() {
                return new Promise((resolve, reject) => {                    
                    this.$refs.fileInput.value = ""
                    this.$refs.croppieRef.refresh()
                    resolve(true)
                })
			}
		},
		watch: {
			'cropped': function(val, oldVal) {
				if(val) {
					this.disabled = false
				} else {
					this.disabled = true
				}
			}
		}
	}
</script>

<style>


</style>