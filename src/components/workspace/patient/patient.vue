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
  methods: {
   
  },
  computed:{
  },
  created() {
    console.log('you are in the patient vue comp');
  }
};
</script>