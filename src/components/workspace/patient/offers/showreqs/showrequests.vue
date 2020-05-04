<template id='temp'>
  <div id="root">
    <router-view></router-view>
  </div>
</template>
<script>
import c from '../../../../../common/common.js';
import $ from "jquery";
export default {
  data() {
    return {
      show_error: false,
      errors: [],
      show:'',
      offers: [],
      req: {},
      state : 'warning'

    };
  },
  methods: {
    doctorreqs() {
      if(this.$router.currentRoute.name != 'doctorreqs'){
        this.$router.push({name:'doctorreqs'});
      }
    },
    pharmacyreqs() {
      if(this.$router.currentRoute.name != 'pharmacyreqs'){
        this.$router.push({name:'pharmacyreqs'});
      }
    },    
    labreqs() {
      if(this.$router.currentRoute.name != 'labreqs'){
        this.$router.push({name:'labreqs'});
      }
    },        

  },
  created() {
    var self = this;
    this.$store.state.sidebar.content = [];
    this.$store.state.sidebar.content.push({
      text:'طلبات الاطباء',
      do(){
        self.doctorreqs();
      }
    })
    this.$store.state.sidebar.content.push({
      text:'طلبات الادوية',
      do(){
        self.pharmacyreqs();
      }
    })
    this.$store.state.sidebar.content.push({
      text:'طلبات التحاليل',
      do(){
        self.labreqs();
      }
    })
    this.$store.state.sidebar.state = true;
    console.log("you are in the you are in patient offers vue comp");
  }
};
</script>
<style scoped>
#root{
  margin: 10px;
}
</style>
