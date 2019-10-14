<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        mounted() {
        }
    }
</script>

<style>
.v-card--reveal {
  align-items    : center;
  bottom         : 0;
  justify-content: center;
  opacity        : .5;
  position       : absolute;
  width          : 100%;
}
.border--radius {
	border-radius: 8px
}
</style>
