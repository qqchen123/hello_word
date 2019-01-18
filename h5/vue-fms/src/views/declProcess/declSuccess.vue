<template>
    <div>
    	<div class="background">
         <input type="text" class="dings" readonly="true" value="5" id="time">
    	</div>
    </div>
</template>

<script>
export default {

  name: 'userpag',

  data () {
    return {
      t: 5
      }
    },
    created: function () {
        setInterval(this.countDown, 1000);
   },
   methods: {
     countDown () {
          if (this.t < 0) {
            return false;
          }
          let time = document.getElementById("time");
          console.log(1);
          time.value = this.t;
          if (this.t <= 0) {
              location.href = "#/userpag";
            }
            this.t--;
        }
   },
   destroyed: function () {
      clearInterval(this.countDown)
    }
}
</script>

<style lang="scss" scoped>
	.background {
		height: rem(1334);
    background: url(i/7.jpg) no-repeat fixed top;
    background-size:100% 100%;
    -moz-background-size:100% 100%;
	}
	.dings {
		padding-top: rem(1060);
		margin-left: 48%;
		font-size: rem(50);
	}
</style>
