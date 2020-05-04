<template>
  <div id="register_root">
    <hdr></hdr>
    <b-container>
      <b-row align-h="center" class="mt-5">
        <b-col cols="14">
          <b-card>
            <h3 class="mb-4">نموذج التسجل</h3>
            <div>
              <b-form>
                <b-alert v-model="show_error" variant="warning" dismissible>
                  <ul>
                    <li v-for="error in errors" :key="error">{{error}}</li>
                  </ul>
                </b-alert>

                <!-- 2 the patient fullname input input -->
                <b-form-group label="الاسم الكامل">
                  <b-form-input
                    :state="i2"
                    type="text"
                    v-model="$store.state.new_patient.username"
                    placeholder="الاسم الكامل"
                  />
                </b-form-group>

                <!-- 3 the email of the patient -->
                <b-form-group label="البريد الالكتروني">
                  <b-form-input
                    :state="i3"
                    type="email"
                    v-model="$store.state.new_patient.email"
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

                <!-- 5 the city of the patient-->
                <b-form-group label="المدينة">
                  <b-form-select
                    :state="i5"
                    v-model="$store.state.new_doctor.city"
                    :options="$store.state.cities"
                    size="sm"
                    class="mt-3"
                  ></b-form-select>
                </b-form-group>

                <!-- 6 the nearest point to the عنوان الطبيبs-->
                <b-form-group label="اقرب نقطة دالة لمكان اقامتك">
                  <b-form-input
                    :state="i6"
                    type="text"
                    v-model="$store.state.new_doctor.np"
                    placeholder="اقرب نقطة دالة لمكان اقامتك"
                  />
                </b-form-group>


                <!-- 7 the phone of the doctor-->
                <b-form-group label="رقم الهاتف">
                  <b-form-input
                    :state="i7"
                    type="number"
                    v-model="$store.state.new_doctor.phone"
                    placeholder="...091 ...0021891 "
                  />
                </b-form-group>

                <!-- 9 gender -->
                <b-form-group label="الجنس">
                  <b-form-radio
                    :class="{red:i9}"
                    v-model="$store.state.new_doctor.gender"
                    value="0"
                  >ذكر</b-form-radio>
                  <b-form-radio
                    :class="{red:i9}"
                    v-model="$store.state.new_doctor.gender"
                    value="1"
                  >انثى</b-form-radio>
                </b-form-group>


                <!--  10 the birth date of the doctor-->
                <b-form-group label="تاريخ الميلاد">
                  <b-input-group class="mb-3">
                    <b-form-input
                      v-model="$store.state.new_doctor.birth"
                      type="text"
                      placeholder="YYYY-MM-DD"
                      :state="i10"
                    ></b-form-input>
                    <b-input-group-append>
                      <b-form-datepicker
                        v-model="$store.state.new_doctor.birth"
                        button-only
                        right
                        locale="en-US"
                      ></b-form-datepicker>
                    </b-input-group-append>
                  </b-input-group>
                </b-form-group>

                <!--  8 the notes of the patient-->
                <b-form-group label="ملاحظات صحية، هذا الحقل اختياري">
                  <b-form-textarea
                    v-model="$store.state.new_patient.note"
                    placeholder="ملاحظات صحية تخصك"
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
      i8: null,
      i9: null,
      i10: null
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
      self.i9 = null;
      self.i10 = null;

      // initializing submit
      var submit = true;

      console.log(self.$store.state.new_patient)
      // making sure that the name is true
      if (!c.isfullname(self.$store.state.new_patient.username)) {
        submit = false;
        self.i2 = false;
        self.errors.push("ادخل الاسم بشكل صحيح");
      }

      // checking for email
      if (!c.isemail(self.$store.state.new_patient.email)) {
        submit = false;
        self.i3 = false;
        self.errors.push("هذا البريد الالكتروني غير صحيح");
      }

      // checking for strength of password
      if (!c.isstrongpassword(self.$store.state.new_patient.password)) {
        submit = false;
        self.i4 = false;
        self.errors.push( "يجب ان يتكون الرمز السري من احوف انجليزية صغيرة وكبيرة ورموز وارقام وان يكون اطول من ثمانية احرف" );
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
      if (!self.$store.state.new_patient.city) {
        submit = false;
        self.i5 = false;
        self.errors.push(
          "اختر مدينة"
        );
      }

      // checking for phone number
      if (!c.phonenumber(self.$store.state.new_patient.phone)) {
        submit = false;
        self.i7 = false;
        self.errors.push(
          "رقم الهاتف غير صحيح يجب ان يبدا 09 او 002189 "
        );
      }

      //checking gender
      if (!self.$store.state.new_patient.gender) {
        submit = false;
        self.i9 = true;
        self.errors.push("اختر الجنس");
      }

      //checking birth
      if (!c.isdate(self.$store.state.new_patient.birth)) {
        submit = false;
        self.i10 = false;
        self.errors.push("ادخل تاريخ ميلاد صحيح");
      }

      //checking that the email is not existing in the data base
      if (submit) {
        console.log("submitting data in newpatient");
        try {
          $.ajax({
            url: self.url,
            method: "POST",
            data: {
              pass: self.$store.state.phrase,
              action: "checkemail",
              email: self.$store.state.new_patient.email
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
                self.show_error =true;
              } else {
                $.ajax({
                  url: self.url,
                  method: "POST",
                  data: {
                    pass: self.$store.state.phrase,
                    action: "register_patient",
                    username: self.$store.state.new_patient.username,
                    password: self.$store.state.new_patient.password,
                    birth: self.$store.state.new_patient.birth,
                    email: self.$store.state.new_patient.email,
                    city: self.$store.state.new_patient.city,
                    np: self.$store.state.new_patient.np,
                    note: self.$store.state.new_patient.note,
                    nationalnumber:
                      self.$store.state.new_patient.nationalnumber,
                    phone: self.$store.state.new_patient.phone,
                    gender: self.$store.state.new_patient.gender
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
                      self.show_error = true;
                    } else {
                      self.$store.state.alerts.type = "rig";
                      self.$store.state.alerts.msg = [
                      "لقد تم تسجيل حسابك بنجاح يرجى تسجيل الدخول الان"
                      ];
                      self.$router.push({ name: "login" });
                    }
                  })
                  .fail(function(data) {
                    console.log(
                      "failed in query registration and data: " + data
                    );
                    submit = false;
                    self.errors.push(
                     self.$store.state.errs.badquery
                    );
                    self.show_error = true;
                  });
              }
            })
            .fail(function(data) {
              console.log(
                "failed in checkign email: " + data.status + " url: " + self.url
              );
              submit = false;
              self.errors.push(                     
                self.$store.state.errs.badquery
              );
              self.show_error = true;
            });
        } catch (err) {
          console.log("error occured in registring ajax, err: " + err);
          self.errors.push(                     
            self.$store.state.errs.badquery
          );
          self.show_error = true;
          self.submit = false;
        }
      }

      //if there is an error show massages
      if (!submit) {
        self.show_error = true;
      }
    }
  }
};
</script>
<style scoped>
.red {
  color: red;
}
</style>