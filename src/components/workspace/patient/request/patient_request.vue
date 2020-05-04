<template id='temp'>
  <div id="root">
    <div id="container">
      <b-alert v-model="show_error" variant="warning" dismissible>
        <ul>
          <li v-for="error in $store.state.alerts.msg" :key="error">{{error}}</li>
        </ul>
      </b-alert>
      <router-view></router-view>
    </div>
  </div>
</template>
<script>
import c from "../../../../common/common";
export default {
  data() {
    return {
      show_error: false
    };
  },
  methods: {
    askdoctor() {
      var self = this;
      self.show_error = false;
      if (self.$router.currentRoute.name != "askdoctor") {
        self.$router.push({ name: "askdoctor" }).then(() => {}).catch(() => {});
        if ((self.$store.state.alerts.type == "preq")) {
          console.log('false')
          self.show_error = true;
          self.$store.state.type = "";
        }
      }
    },
    askpharmacy() {
      var self = this;
      self.show_error = false;
      if (self.$router.currentRoute.name != "askpharmacy") {
        self.$router.push({ name: "askpharmacy" }).then(() => {}).catch(() => {});
        if ((self.$store.state.alerts.type == "preq")) {
          console.log('false')
          self.show_error = true;
          self.$store.state.type = "";
        }
      }
    },
    asklab() {
      var self = this;
      self.show_error = false;
      if (self.$router.currentRoute.name != "asklab") {
        self.$router.push({ name: "asklab" }).then(() => {}).catch(() => {});
        if ((self.$store.state.alerts.type == "preq")) {
          console.log('false')
          self.show_error = true;
          self.$store.state.type = "";
        }
      }
    },    
  },
  created() {    
    var self = this;
    this.$store.state.sidebar.content = [];
    this.$store.state.sidebar.content.push({
      text:'طلب طبيب',
      do(){
        self.askdoctor();
      }
    })
    this.$store.state.sidebar.content.push({
      text:'طلب دواء',
      do(){
        self.askpharmacy();
      }
    })
    this.$store.state.sidebar.content.push({
      text:'طلب تحليل',
      do(){
        self.asklab();
      }
    })
    this.$store.state.sidebar.state = true;
    console.log('you are in the patinet_request vue comp');
  }
};
</script>
<style scoped>
#root{
  margin: 10px;
}
</style>