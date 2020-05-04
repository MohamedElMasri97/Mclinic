<template id='temp'>
  <div id="root">
    <div class="header" v-if="show_error">
        <b-alert v-model="show_error" :variant="state" dismissible class="alert">
        <ul>
            <li v-for="error in errors" :key="error">{{error}}</li>
        </ul>
        </b-alert>
    </div>
    <div class="header">
      <h4>طلباتك</h4>
      <div><b-button class="backbtn" @click="$event.preventDefault;refresh(element)">Refresh</b-button></div>
    </div>
    <div v-if="show" class="header">
      <b-card-group deck style="width:100%;margin:0 auto;">
        <b-row v-for="element in reqs" :key="element.id" style="width:100%;margin:10px;">
          <b-card class="cardcolor">
            <b-card-text>{{short(element.note)}}</b-card-text>
            <template v-slot:footer>
              <p>مكان الطلب: {{element.np}}, {{$store.state.citymap[element.city]}}.</p>
              <p>وقت الطلب: {{element.ts}}</p>
              <hr  class='hr' />
              <b-button class="backbtn" @click="$event.preventDefault;details(element)">التفاصيل</b-button>
            </template>
          </b-card>
          <hr  class='hr' />
        </b-row>
      </b-card-group>
    </div>
  </div>
</template>
<script>
import c from '../../../../../../../common/common.js';
import $ from "jquery";
export default {
  data() {
    return {
      show_error: false,
      errors: [],
      reqs: [],
      state : 'warning',
      show:false
    };
  },
  methods: {
    short(element){
      return c.short(element);
    },
    refresh(){
      var self = this;
      self.show = false;
      self.state = 'warning';
      self.reqs = [];
      self.show_error = false;
      self.errors = [];
      c.checkme();
      $.ajax({
        method:'POST',
        url:self.$store.state.url,
        data:{
          pass:self.$store.state.phrase,
          action:'fetch_patient_reqs_for_pharmacy',
          email:localStorage.email
        }
      }).done(function(data){
        var results = [];
        try{
          results = JSON.parse(data);
          console.log('successfull query and results is ' + results);
          if(results[0]){
            self.reqs = results[1];
            self.show = true;
          }else{
            self.errors.push('You have no pharmacy requests.');
            self.show_error =true;
          }
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
        }catch(err){
          console.log('successfull query and error is ' + err + ' and data is ' + data);
          self.errors =  ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
          self.show_error =true;
        }

      }).fail(function(data){
        console.log('successfull query and data is ' + data);
        self.show_error = true;
        self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
      })
    },
    details(element){
      this.$router.push({name:'pharmacyoffers',params:{req_id:element.req_id}})
    }
  },
  created() {
    var self = this;
    self.refresh();
    console.log("you are in the pharmacyreqs vue comp");
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
.hr {
  margin: 10px auto;
}
#root{
  margin: 10px;
}
.cardcolor {
  background:rgb(169, 224, 255);
}
</style>