<template id='temp'>
  <div id="root">
    <div id="container">
      <router-view></router-view>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
    };
  },
  created() {


    console.log('you are in the doctor vue comp');
  }
};
</script>
<style scoped>
body,
html,
#root,
#temp,
#container {
  padding:1%;
  /* initiation */
  margin: 0;
  height:inherit;
}
</style>
