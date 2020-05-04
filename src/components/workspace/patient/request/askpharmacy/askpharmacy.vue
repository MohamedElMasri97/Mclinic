<template>
  <div id="root">
    <div class="header" v-if="show_error">
      <b-alert v-model="show_error" :variant="state" dismissible class="alert">
        <ul>
          <li v-for="error in errors" :key="error">{{error}}</li>
        </ul>
      </b-alert>
    </div>
    <b-form @submit="onSubmit" v-if="show" class="header">
      <b-form-group
        id="input-group-1"
        label="ادرج الادوية المطلوبة"
        description="يفضل ان تدرج كما في الوصفة الطبية ان كانت موجودة"
        label-for="input-1"
      >
        <b-form-textarea
          id="textarea"
          v-model="form.note"
          placeholder="الادوية..."
          rows="5"
          max-rows="6"
          required
        ></b-form-textarea>
      </b-form-group>

      <b-form-group id="input-group-2" label="ما حاجت لهذا الدواء" label-for="input-2">
        <b-form-input
          id="input-2"
          v-model="form.why"
          type="text"
          required
          placeholder="مثلا....اشعر بصداع شديد"
        ></b-form-input>
      </b-form-group>

      <b-form-group
        id="input-group-4"
        label="من فضلك ادرج الباركود الخاص بالدواء ان كان موجود"
        label-for="input-4"
      >
        <b-form-input
          id="input-4"
          v-model="form.barcode"
          type="text"
          placeholder="الباركود..."
        ></b-form-input>
      </b-form-group>

      <b-form-group id="input-group-3" label="المديمة" label-for="input-3">
        <b-form-select id="input-3" v-model="form.city" :options="$store.state.cities" required></b-form-select>
      </b-form-group>

      <b-form-group
        id="input-group-5"
        label="اقرب نقطة دالة لموقعك الحالي"
        label-for="input-5"
      >
        <b-form-input
          id="input-5"
          v-model="form.np"
          type="text"
          required
          placeholder="اقرب نقطة دالة"
        ></b-form-input>
      </b-form-group>

      <b-button type="submit" class="backbtn">طلب</b-button>
    </b-form>
  </div>
</template>
<script>
import c from "../../../../../common/common.js";
import $ from "jquery";
export default {
  data() {
    return {
      show: true,
      form: {
        why: "",
        note: "",
        city: "",
        np: "",
        barcode: ""
      },
      state: "warning",
      show_error: false,
      errors: []
    };
  },
  created() {
    var self = this;
    c.checkreq("2").then(function(state) {
      if (state == 2) {
        self.$router.push({
          name: "requesthome",
          params: {
            alert: {
              msg: "عذرا لقد تجاوزت الحد الاقصى للطلبات",
              state: "warning"
            }
          }
        });
      } else if (state == 3) {
        self.$router.push({
          name: "requesthome",
          params: {
            alert: {
              msg: "عفوا حدث خطأ حاول مرة اخرى لاحقا",
              state: "warning"
            }
          }
        });
      }
    });
  },
  methods: {
    onSubmit(evt) {
      var self = this;
      // prevent submitting
      evt.preventDefault();

      //initializign errors
      self.errors = [];
      self.show_error = false;
      self.state = "warning";
      c.checkme();
      // query
      $.ajax({
        method: "POST",
        url: self.$store.state.url,
        data: {
          pass: self.$store.state.phrase,
          action: "rig_pharmacy_req",
          city: self.form.city,
          why: self.form.why,
          note: self.form.note,
          barcode: self.form.barcode,
          np: self.form.np,
          email: localStorage.email
        }
      })
        .done(function(data) {
          try {
            var results = JSON.parse(data);
            console.log("done response at ask pharmacy, results: " + results);
            if (results[0]) {
              self.$router.push({
                name: "requesthome",
                params: {
                  alert: {
                    msg:
                      "لقد تم تسجيل طلبك بنجاح، و سيصلك تنبيه في حال وصول عروض",
                    state: "success"
                  }
                }
              });
              self.show = false;
            } else {
              self.state = "warning";
              self.show_error = true;
              self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
            }
          } catch (err) {
            console.log(
              "fail response at ask pharmacy, err: " + err + ", data " + data
            );
            self.state = "warning";
            self.show_error = true;
            self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
          }
        })
        .fail(function(data) {
          console.log("fail response at ask pharmacy,  data " + data);
          self.state = "warning";
          self.show_error = true;
          self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
        });
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