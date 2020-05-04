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
      <h3 class="title">بيانات العرض</h3>
      <b-button class="backbtn" @click="$event.preventDefault;refresh(element)">Refresh</b-button>
      <b-button class="backbtn" @click="$event.preventDefault;back()">رجوع</b-button>
    </div>
    <div v-if="show" class="header">
      <h4>تفاصيل الطلب</h4>        
      <p>نص الطلب: {{req.note}}</p>
      <hr  class='hr' />
      <p>سبب الطلب: {{req.why}}</p>
      <hr  class='hr' />
      <p>مكان الطلب: {{req.np}}, {{$store.state.citymap[req.city]}}</p>
      <hr  class='hr' />
      <small>وقت الطلب: {{req.ts}}</small>
      <hr  class='hr' />
      <h4>تفاصيل العرض</h4>       
      <p>العرض المقدم من المعمل:  {{offer.offernote}}</p>
      <hr  class='hr' />
      <p>اسم المعمل:  {{provider.usernmae}}</p>
      <hr  class='hr' />
      <p>عنوان المعمل:  {{provider.np}}, {{$store.state.citymap[provider.city]}}</p>
      <hr  class='hr' />
      <p>توقيت الموعد:  {{offer.appointment}}</p>
      <hr  class='hr' />
      <p>سعر العرض:  {{offer.pricing}}</p>
      <hr  class='hr' />
      <small>الوقت الذي ارسل فيه العرض: {{req.ts}}</small>
      <hr  class='hr' />
      <b-button class="backbtn" @click="$event.preventDefault;accept()">قبول الطلب</b-button>
    </div>
  </div>
</template>
<script>
import c from '../../../../../../../common/common.js';
import $ from 'jquery';
export default {
    data(){
        return{
            show_error:false,
            errors:[],
            offer:{},
            req:{},
            state:'warning',
            provider:{},
            show:false
        }
    },
    created(){
        var self = this
        if(!(self.$route.params.req_id && self.$route.params.offerid && self.$route.params.providerid) ){
            self.$router.push({name:'labreqs'});
        }else{
            self.refresh();
        }

    },
    methods:{
        back(){
            var self = this;
            this.$router.push({name:'laboffers',params:{req_id:self.$route.params.req_id}})
        },
        refresh(){
            var self = this;
            self.offers = [];
            self.req = {};
            self.provider = {};
            self.show_error = false;
            self.show = false;
            self.state = 'warning';
            self.errors = [];
            c.checkme();
            $.ajax({
                method:'POST',
                url:self.$store.state.url,
                data:{
                pass:self.$store.state.phrase,
                action:'fetch_patient_detailed_lab_offer',
                req_id:self.$route.params.req_id,
                offerid:self.$route.params.offerid,
                providerid:self.$route.params.providerid
                }
            }).done(function(data){
                var results = [];
                try{
                results = JSON.parse(data);
                console.log('successfull query and results is ' + results);
                if(results[0]){
                    self.req = results[2];
                    self.provider = results[3];
                    self.offer = results[1];
                    self.show = true;
                }else{
                    self.errors =  ['بيانات هذا الطلب غير صحيحة'];
                    self.show_error =true;
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
            });
        },
        accept(){
            var self = this;
            self.offers = [];
            self.show_error = false;
            self.show = false;
            self.state = 'warning';
            self.errors = [];
            c.checkme();
            $.ajax({
                method:'POST',
                url:self.$store.state.url,
                data:{
                pass:self.$store.state.phrase,
                action:'accept_offer',
                req_id:self.$route.params.req_id,
                oid:self.$route.params.offerid,
                type:'3'
                }
            }).done(function(data){
                var results = [];
                try{
                results = JSON.parse(data);
                console.log('successfull query and results is ' + results);
                if(results[0]){
                    self.$router.push({name:'labreqs',params:{alert:{state:'success',msg:'The offer has been accepted and moved to appontments.'}}});
                }else{
                    self.errors =  ['بيانات هذا الطلب غير صحيحة'];
                    self.show_error =true;
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
            });
        }
    },
}
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
</style>
