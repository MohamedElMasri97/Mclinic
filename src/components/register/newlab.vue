<template>
  <div id="register_root">
    <hdr></hdr>
    <b-container>
      <b-row align-h="center" class="mt-5">
        <b-col cols="14">
          <b-card class="p-3">
            <h3 class="mb-4">نموذج التسجل</h3>
            <div>
              <b-form>
                <b-alert v-model="show_error" variant="warning" dismissible>
                  <ul>
                    <li v-for="error in errors" :key="error">{{error}}</li>
                  </ul>
                </b-alert>

                <!-- 1 the title input -->
                <b-form-group label="اسم المعمل: ">
                  <b-form-input
                    :state="i1"
                    type="text"
                    v-model.trim="$store.state.new_lab.title"
                    placeholder="اسم المعمل: "
                  />
                </b-form-group>

                <!-- 2 the manager name input input -->
                <b-form-group label="اسم المالك او المشرف">
                  <b-form-input
                    :state="i2"
                    type="text"
                    v-model="$store.state.new_lab.username"
                    placeholder="اسم المالك او المشرف"
                  />
                </b-form-group>

                <!-- 3 the email of the lab -->
                <b-form-group label="البريد الالكتروني">
                  <b-form-input
                    :state="i3"
                    type="email"
                    v-model="$store.state.new_lab.email"
                    placeholder="البريد الالكتروني"
                  />
                </b-form-group>


                <!-- 4 the password of the doctor -->
                <b-form-group
                  label="الرمز السري"
                  description="يجب ان يتكون الرمز السري من احوف انجليزية صغيرة وكبيرة ورموز وارقام وان يكون اطول من ثمانية احرف"
                >
                  <b-form-input
                    :state="i4"
                    type="password"
                    v-model="$store.state.new_doctor.password"
                    placeholder="الرمز السري"
                  />
                </b-form-group>

                <!-- 4 the password of the doctor -->
                <b-form-group
                  label="تأكيد الرمز السري"
                >
                  <b-form-input
                    :state="i44"
                    type="password"
                    v-model="$store.state.new_doctor.password2"
                    placeholder="تأكيد الرمز السري"
                  />
                </b-form-group>


                <!-- 5 the city of the lab-->
                <b-form-group label="المدينة">
                  <b-form-select
                    :state="i5"
                    v-model="$store.state.new_lab.city"
                    :options="$store.state.cities"
                    size="sm"
                    class="mt-3"
                  ></b-form-select>
                </b-form-group>

                <!-- 6 the nearest point to the lab-->
                <b-form-group label="اقرب نقطة دالة لعنوان المعمل">
                  <b-form-input
                    :state="i6"
                    type="text"
                    v-model="$store.state.new_lab.np"
                    placeholder="اقرب نقطة دالة"
                  />
                </b-form-group>

                <!-- 7 the phone of the lab-->
                <b-form-group label="رقم الهاتف">
                  <b-form-input
                    :state="i7"
                    type="number"
                    v-model="$store.state.new_lab.phone"
                    placeholder="...091 ...0021891 "
                  />
                </b-form-group>

                <!--  8 the phone of the lab-->
                <b-form-group label="ملاحظات">
                  <b-form-textarea
                    v-model="$store.state.new_lab.note"
                    placeholder="ملاحظات..."
                    rows="3"
                    max-rows="6"
                  ></b-form-textarea>
                </b-form-group>

                <b-button variant="primary" @click="check">انشئ</b-button>
                <hr />
                <router-link :to="{name:'login'}">لدي حساب بالفعل</router-link>
              </b-form>
            </div>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
import $ from "jquery";
import c from "../../common/common.js";
import hdr from "./../login_hdr";
export default {
  components: {
    hdr
  },
  created() {
    localStorage.clear();
    if (!this.$store.state.new_what) {
      this.$router.push({ name: "register" });
    }
  },

  data() {
    return {
      url: this.$store.state.url,

      show_error: false,

      errors: [],

      i1: null,
      i2: null,
      i3: null,
      i4: null,
      i44: null,
      i5: null,
      i6: null,
      i7: null,
      i8: null
    };
  },

  methods: {
    check(evt) {
      // preventing the button from doing any thing weard
      evt.preventDefault();

      //initializing states of inputs
      var self = this;
      self.show_error = false;
      self.errors = [];
      self.i1 = null;
      self.i2 = null;
      self.i3 = null;
      self.i4 = null;
      self.i44 = null;
      self.i5 = null;
      self.i6 = null;
      self.i7 = null;
      self.i8 = null;

      // initializing submit
      var submit = true;

      // checking that title is longer then 4 letters
      if (self.$store.state.new_lab.title.length < 3) {
        submit = false;
        self.i1 = false;
        self.errors.push("يجب ان يكون اسم المعمل:  اطول من ثلاثة حروف");
      }

      // making sure that the maneger name is true
      if (!c.isfullname(self.$store.state.new_lab.username)) {
        submit = false;
        self.i2 = false;
        self.errors.push("ادخل الاسم بشكل صحيح");
      }

      // checking for email
      if (!c.isemail(self.$store.state.new_lab.email)) {
        submit = false;
        self.i3 = false;
        self.errors.push("هذا البريد الالكتروني غير صحيح");
      }

      // checking for strength of password
      if (!c.isstrongpassword(self.$store.state.new_lab.password)) {
        submit = false;
        self.i4 = false;
        self.errors.push(
          "يجب ان يتكون الرمز السري من احوف انجليزية صغيرة وكبيرة ورموز وارقام وان يكون اطول من ثمانية احرف"
        );
      }
      // checking for confifmation of password
      if (self.$store.state.new_doctor.password != self.$store.state.new_doctor.password2) {
        submit = false;
        self.i44 = false;
        self.errors.push(
          "يجب ان يكون تأكيد الرمز السري مطابقا للرمز المدخل"
        );
      }

      // checking for city
      if (!self.$store.state.new_lab.city) {
        submit = false;
        self.i5 = false;
        self.errors.push(
          "اختر مدينة"
        );
      }

      // checking for phone number
      if (!c.phonenumber(self.$store.state.new_lab.phone)) {
        submit = false;
        self.i7 = false;
        self.errors.push(
          "رقم الهاتف غير صحيح يجب ان يبدا 09 او 002189 "        
        );
      }

      //checking that the email is not existing in the data base
      if (submit) {
        $.ajax({
          url: self.url,
          method: "POST",
          data: {
            pass: self.$store.state.phrase,
            action: "checkemail",
            email: self.$store.state.new_lab.email
          }
        })
          .done(function(data) {
            console.log(
              "successfull query for checking email and data: " + data
            );
            var results = JSON.parse(data);
            if (!results[0]) {
              submit = false;
              self.i3 = false;
              self.errors.push("هذا البريد موجود بالفعل");
            } else {
              $.ajax({
                url: self.url,
                method: "POST",
                data: {
                  pass: self.$store.state.phrase,
                  action: "register_lab",
                  username: self.$store.state.new_lab.username,
                  password: self.$store.state.new_lab.password,
                  title: self.$store.state.new_lab.title,
                  email: self.$store.state.new_lab.email,
                  city: self.$store.state.new_lab.city,
                  np: self.$store.state.new_lab.np,
                  note: self.$store.state.new_lab.note,
                  phone: self.$store.state.new_lab.phone
                }
              })
                .done(function(data) {
                  console.log(
                    "successfule query in registring and data:" + data
                  );
                  var results = JSON.parse(data);
                  if (!results[0]) {
                    submit = false;
                    self.errors.push(
                      self.$store.state.errs.badquery
                    );
                  } else {
                    self.$store.state.alerts.type = "rig";
                    self.$store.state.alerts.msg = [
                      "لقد تم تسجيل حسابك بنجاح يرجى تسجيل الدخول الان"
                    ];
                    self.$router.push({ name: "login" });
                  }
                })
                .fail(function(data) {
                  console.log("failed in query registration and data: " + data);
                  submit = false;
                  self.errors.push(
                    self.$store.state.errs.badquery
                  );
                });
            }
          })
          .fail(function(data) {
            console.log(
              "failed in checkign email: " + data.status + " url: " + self.url
            );
            submit = false;
            self.errors.push(self.$store.state.errs.badquery);
          });
      }

      //if there is an error show massages
      if (!submit) {
        self.show_error = true;
      }
    }
  }
};
</script>