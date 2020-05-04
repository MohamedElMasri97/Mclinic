<template id='temp'>
  <div id="root">
    <div class="header" v-if="show_error">
      <b-alert v-model="show_error" :variant="state" dismissible class="alert">
        <ul>
          <li v-for="error in errors" :key="error">{{error}}</li>
        </ul>
      </b-alert>
    </div>
    <div></div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      state : 'warning',show_error:false,errors:[]
    };
  },
  created() {
    var self = this;          
    try{
        if(self.$route.params.alert.state != ''){
            self.state = self.$route.params.alert.state;
            self.errors.push(self.$route.params.alert.msg);
            self.show_error =true;
            self.$route.params.alert.state = '';
        }
    }catch(err){
        console.log('error ' + err);
    }
  }
};
</script>
<style scoped>
.alert {
  margin: 0px;
}
.header {
  background: rgb(243, 243, 243);
  margin: 0px;
  padding:12px;
  width: 100%;
}
.backbtn {
  background: rgb(63, 63, 63);
  color: rgb(255, 255, 255);
}
#root{
  margin: 10px;
}
</style>