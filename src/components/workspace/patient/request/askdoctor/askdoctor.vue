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
      <b-form-group id="input-group-1" label="لماذا تريد طلب طبيب" label-for="input-1">
        <b-form-textarea
          id="textarea"
          v-model="form.note"
          placeholder="ماذا تشعر..."
          rows="5"
          max-rows="6"
          required
        ></b-form-textarea>
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

      <b-form-group id="input-group-4" label="ماهو جنس الطبيب المفضل" label-for="input-4">
        <b-form-select
          id="input-4"
          v-model="form.gender"
          :options="$store.state.genderchoice"
          required
        ></b-form-select>
      </b-form-group>

      <b-button type="submit" class="backbtn">طلب</b-button>
    </b-form>
  </div>
</template>
<script>
import c from "../../../../../common/common";
import $ from "jquery";
export default {
  data() {
    return {
      show: true,
      form: {
        gender: "",
        note: "",
        city: "",
        np: ""
      },
      state: "warning",
      show_error: false,
      errors: []
    };
  },
  created() {
    var self = this;
    c.checkreq("1").then(function(state) {
      if (state == 2) {
        self.$router.push({
          name: "requesthome",
          params: {
            alert: {
              msg: "لقد تجاوزت الحد المسموح به للطلبات",
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

      // query
      try {
        c.checkme();
        $.ajax({
          method: "POST",
          url: self.$store.state.url,
          data: {
            pass: self.$store.state.phrase,
            action: "rig_doctor_req",
            city: self.form.city,
            gender: self.form.gender,
            note: self.form.note,
            np: self.form.np,
            email: localStorage.email
          }
        })
          .done(function(data) {
            try {
              var results = JSON.parse(data);
              console.log("successfull query and results is" + results);
              if (results[0]) {
                self.$router.push({
                  name: "requesthome",
                  params: {
                    alert: {
                      msg: "لقد تم تسجيل طلبك بنجاح، و سيصلك تنبيه في حال وصول عروض",
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
                "fail response at ask doctor err is " +
                  err +
                  " and data is " +
                  data
              );
              self.state = "warning";
              self.show_error = true;
              self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
            }
          })
          .fail(function(data) {
            console.log(data);
            self.state = "warning";
            self.show_error = true;
            self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
          });
      } catch (err) {
        console.log(err);
        self.state = "warning";
        self.show_error = true;
        self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
      }
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