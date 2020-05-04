<template>
  <div id="root">
    <b-alert v-model="show_error" variant="warning" dismissible>
      <ul>
        <li v-for="error in errors" :key="error">{{error}}</li>
      </ul>
    </b-alert>
    <hr />
    <p>اسم المستخدم:  {{$store.state.lab_offer_element.name}}</p>
    <hr />
    <p>جنس المستخدم:   {{$store.state.gendermap[$store.state.lab_offer_element.gender]}}</p>
    <hr />
    <p>تاريخ ميلاد المستخدم:   {{$store.state.lab_offer_element.birth}}</p>
    <hr />
    <p>المدينة:   {{$store.state.citymap[$store.state.lab_offer_element.city]}}</p>
    <hr />
    <p>اقرب نقطة دالة:   {{$store.state.lab_offer_element.np}}</p>
    <hr />
    <p>الهاتف:  {{$store.state.lab_offer_element.phone}}</p>
    <hr />
    <p>وقت الطلب: {{$store.state.lab_offer_element.ts}}</p>
    <hr />
    <p>سبب طلب هذا التحليل: {{$store.state.lab_offer_element.why}}</p>
    <hr />
    <p>{{$store.state.lab_offer_element.note}}</p>
    <hr />
    <b-form @submit="onSubmit" >
      <b-form-group
        id="input-group-1"
        label="ماذا تود ان تقدم لهذا المستخدم: "
        label-for="input-1"
      >
        <b-form-textarea
          id="textarea"
          v-model="form.note"
          placeholder="ماذا تود ان تقدم لهذا المستخدم..."
          rows="5"
          max-rows="6"
          required
        ></b-form-textarea>
      </b-form-group>

      <b-form-group
        id="input-group-6"
        label="الموعد الذي تود مقابلته فيه، يفضل ان توفر توقيت معين"
        label-for="input-6"
      >
        <b-form-input
          id="input-6"
          v-model="form.time"
          type="text"
          required
          placeholder="الموعد..."
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-5" label="هل هاك سعر معين مقابل هاذه الخدمات:  " label-for="input-5">
        <b-form-input
          id="input-5"
          v-model="form.pricing"
          type="text"
          required
          placeholder="هل هناك سعر معين مقابل هاذه الخدمات..."
        ></b-form-input>
      </b-form-group>

      <b-button type="submit" variant="primary">ارسال</b-button>
      <b-button type="cancel" variant="primary" @click="onCancel">رجوع</b-button>
    </b-form>
  </div>
</template>
<script>
import c from '../../../../common/common.js';
import $ from "jquery";
export default {
  created(){
    var self = this;
      console.log(self.$store.state.lab_offer_element);

    if(!self.$store.state.lab_offer_element){
      self.$router.push({name:'lab_trending'})
    }
  },
  data() {
    return {
      form: {
        note:'',
        pricing:'',
        time:''
      },
      show_error:false,
      errors: []
    };
  },
  methods: {
    onCancel() {
      this.$router.push({ name: "lab_trending" });
    },
    onSubmit(evt) {
      evt.preventDefault();
      var self = this;
      console.log("submitting");
        c.checkme();
        $.ajax({
          method: "POST",
          url: self.$store.state.url,
          data: {
            action: "rig_lab_offer",
            pass: self.$store.state.phrase,
            patientid: self.$store.state.lab_offer_element.id,
            reqid: self.$store.state.lab_offer_element.req,
            email: localStorage.email,
            note: self.form.note,
            time: self.form.time,
            pricing: self.form.pricing
          }
        }).done(function(data) {
          console.log(
            "successfulle query on rigistring offer and data is " + data
          );
          try {
            var results = JSON.parse(data);
          } catch (err) {
            console.log("wrong data and data is " + data);
            var results = [false];
          }
          if (results[0]) {
            self.$store.state.msg = [
              "تم ارسال عرضك بنجاح"
            ];
            self.$store.state.type = "success";
            self.$router.push({ name: "lab_trending" });
          } else {
            self.show_error =true;
            self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
          }
        }).fail(function(data){
            console.log('the qury failed and the data is ' + data );
            self.show_error =true;
            self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
        });
      }
    }
  }

</script>
<style scoped>
</style>
