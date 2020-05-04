
<template>
   <div id="root">
    <div>
    </div>
    <b-alert v-model="show_error" class="alert" :variant="state" dismissible>
      <ul>
        <li v-for="error in errors" :key="error">{{error}}</li>
      </ul>
    </b-alert>
    <hr />
    <b-card-group deck v-if="showcard">
      <b-row v-for="element in elements" :key="element.offer.offerid" class="mrg">
        <b-card class="cardcolor">
          <b-card-text>
            <p class="inline"><span>اسم المستخدم</span> <span> {{element.patient.username}}</span> 
               <span> متواجد في </span>  <span> {{element.req.np}},</span>  <span> element.req.city,</span>
                 <span> و نص الطلب</span><span> "{{short(element.req.note)}}"</span><span> ,وقت الطلب</span> <span> {{element.req.ts}}</span> , <span> وكان عرضك على هذا الطلب</span><span> "{{short(element.offer.offernote)}}"</span>
                <span> وسعر العرض</span> <span> "{{element.offer.pricing}}" </span> <span> و نص الموعد</span> <span> "{{element.offer.appointment}}"</span> 
            </p>
            <hr />
            <b-button variant="primary" @click="card=element;showcard=!showcard;">التفاصيل</b-button>
          </b-card-text>
        </b-card>
        <hr />
      </b-row>
    </b-card-group>
    <b-card-group deck v-if="!showcard">
      <b-row class="mrg">
        <b-card>
          <b-card-text>
            <h4>بيانات المستخدم</h4>
            <p>اسم المستخدم:  {{card.patient.username}}</P>
            <hr />
            <p >نص الطلب: {{card.req.note}}</p>
            <hr />
            <p >مدينة المستخدم:  {{$store.state.citymap[card.req.city]}}</p>
            <hr />
            <p >اقرب نقطة دالة: {{card.req.np}}</p>
            <hr />
            <p >الهاتف: {{card.patient.phone}}</p>
            <hr />
            <p >وقت الطلب: {{card.req.ts}}</p>
            <hr />
            <h4>بيانات العرض الخاص بك</h4>
            <p >نص العرض : {{card.offer.offernote}}</p>
            <hr />
            <p >سعر العرض:  {{card.offer.pricing}}</p>
            <hr />
            <p>توقيت الموعد:  {{card.offer.appointment}}</p>
            <hr />
            <p>الوقت الذي ارسل فيه العرض: {{card.offer.ts}}</p>
            <hr />
            <b-button variant="warning" @click="del(card);fetch();delstatement();">حذف العرض</b-button>
            <b-button variant="warning" @click="showcard = !showcard; card = {};">رجوع</b-button>
          </b-card-text>
        </b-card>
        <hr />
      </b-row>
    </b-card-group>
  </div>
</template>
<script>
import $ from "jquery";
import c from '../../../../common/common.js';
export default {
  data() {
    return {
      showcard:true,
      card:{},
      show_error: false,
      state: "warning",
      errors: [],
      elements: [],
      limit: 100
    };
  },
  created() {
    this.fetch();
  },
  methods: {
    short(element){
      return c.short(element);
    },
    fetch() {
      c.checkme();
      var self = this;
      self.errors = [];
      self.elements = null;
      self.state = "warning";
      self.show_error = false;
      $.ajax({
        method: "POST",
        url: self.$store.state.url,
        data: {
          pass: self.$store.state.phrase,
          action:'fetch_doctor_offers',
          email: localStorage.email,
          limit: self.limit
        }
      })
        .done(function(data) {
          var results = [];
          try {
            results = JSON.parse(data);
            if (results[0]) {
              console.log('successfull query and the data is ' + results)
              self.elements = results[1];
              console.log(results[1]);
              console.log(self.elements[0].patient);
            } else {
              console.log("error while fetching patients ,data: " + data);
              self.errors = ["لم تقم بتقديم اي عرض"];
              self.state = "warning";
              self.show_error = true;
            }
          } catch (err) {
            console.log(
              "error while fetching patients, err " + err + " ,data: " + data
            );
            self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
            self.state = "warning";
            self.show_error = true;
          }
        })
        .fail(function(data) {
          console.log("error while fetching patients data: " + data);
          self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
          self.state = "warning";
          self.show_error = true;
        });
    },
    del(element){
      var self = this;
      c.checkme();
      self.errors = [];
      self.state = "warning";
      self.show_error = false;
      $.ajax({
        method: "POST",
        url: self.$store.state.url,
        data: {
          pass: self.$store.state.phrase,
          action:'del_doctor_offer',
          offer: element.offer.offerid
        }
      })
        .done(function(data) {
          var self = this;
          var results = [];
          try {
            results = JSON.parse(data);
            if (results[0]) {
              console.log('successfull query and the data is ' + results)
            } else {
              console.log("error while fetching patients ,data: " + data);
              self.errors = ["لم تقم بتقديم اي عرض"];
              self.state = "warning";
              self.show_error = true;
            }
          } catch (err) {
            console.log(
              "error while fetching patients, err " + err + " ,data: " + data
            );
            self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
            self.state = "warning";
            self.show_error = true;
          }
        })
        .fail(function(data) {
          console.log("error while fetching patients data: " + data);
          self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
          self.state = "warning";
          self.show_error = true;
        });
    },
    delstatement(){
      var self = this;
      self.errors = ["تم حذف هذا العرض بنجاح"];
      self.state = "success";
      self.show_error = true;
    }
  }
};
</script>
<style scoped>
#root {
  padding: 1rem;
  margin: 0 auto;
}
.mrg{
  margin: 6px auto;
  width:100%;
}.cardcolor{
  color: white;
  background:rgba(57, 91, 163, 0.7);
}

.inline span{display:inline-block}
</style>