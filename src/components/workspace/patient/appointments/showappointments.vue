<template id='temp'>
  <div id="root">
    <router-view></router-view>
  </div>
</template>
<script>
import c from '../../../../common/common.js';
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
      if(this.$router.currentRoute.name != 'patientdoctorappoinments'){
        this.$router.push({name:'patientdoctorappointments'});
      }
    },
    pharmacyreqs() {
      if(this.$router.currentRoute.name != 'patientpharmacyappoinments'){
        this.$router.push({name:'patientpharmacyappointments'});
      }
    },    
    labreqs() {
      if(this.$router.currentRoute.name != 'patientlabappoinments'){
        this.$router.push({name:'patientlabappointments'});
      }
    },        

  },
  created() {
    var self = this;
    this.$store.state.sidebar.content = [];
    this.$store.state.sidebar.content.push({
      text:'مواعيد الاطباء ',
      do(){
        self.doctorreqs();
      }
    })
    this.$store.state.sidebar.content.push({
      text:'حجوزات الادوية',
      do(){
        self.pharmacyreqs();
      }
    })
    this.$store.state.sidebar.content.push({
      text:'مواعيد التحاليل',
      do(){
        self.labreqs();
      }
    })
    this.$store.state.sidebar.state = true;
    console.log("you are in the you are in patient appintments vue comp");
  }
};
</script>
<style scoped>
#root{
  margin: 10px;
}
</style>
