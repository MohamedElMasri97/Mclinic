<template>
  <div id="login_root">
    <hdr></hdr>
    <b-container>
      <b-row align-h="center" class="mt-5">
        <b-col cols="14">
          <b-card >
            <b-alert v-model="show_error" :variant="state" dismissible>
              <ul>
                <li v-for="error in errors" :key="error">{{error}}</li>
              </ul>
            </b-alert>
            <h3 class="mb-4">تسجيل الدخول</h3>
            <div>
              <b-form>
                <b-form-group
                  id="input-group-1"
                  label="البريد الالكتروني"
                  label-for="input-1"
                >
                  <b-form-input
                    id="input-1"
                    :state="email_state"
                    v-model="email"
                    type="email"
                    required
                    placeholder="البريد الالكتروني"
                  ></b-form-input>
                </b-form-group>

                <b-form-group id="input-group-2" label="الرمز السري" label-for="input-2">
                  <b-form-input
                    id="input-2"
                    v-model="password"
                    type="password"
                    required
                    placeholder="الرمز السري"
                  ></b-form-input>
                </b-form-group>

                <b-button type="submit" variant="primary" @click="check">تسجيل الدخول</b-button>
                <hr />
                <router-link :to="{name:'register'}">.ليس لديك حساب؟ قم بانشاء حساب الان</router-link>
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
  created() {
    if(this.$store.state.alerts.type == 'rig'){
      this.$store.state.alerts.type = '';
      this.errors = this.$store.state.alerts.msg;
      this.state = 'success',
      this.show_error = true;
    }else{
      this.show_error= false;
      this.errors = [];
      this.state = 'warning'
    }
  },
  components: {
    hdr
  },
  data() {
    return {
      errors: [],
      show_error: false,
      email_state: null,
      email: "",
      password: "",
      stay: "",
      state: 'warning'
    };
  },
  methods: {
    check(evt) {
      var self = this;
      evt.preventDefault();
      self.state = 'warning';
      self.$store.state.alert = [];
      self.show_error = false;
      self.errors = [];
      self.email_state = null;
      if (c.isemail(this.email) && this.password) {
        $.ajax({
          url: self.$store.state.url,
          method: "POST",
          data: {
            pass: self.$store.state.phrase,
            action: "login",
            email: self.email,
            password: self.password
          }
        })
          .always(function(data) {})
          .done(function(data) {
            console.log(data);
            var results = JSON.parse(data);
            if (results[0]) {
              localStorage.email = self.email;
              localStorage.username = results[2];
              localStorage.usertype = results[3];
              localStorage.password = results[1];
              self.$router.push({ name: "root" });
            } else {
              self.errors.push("من فضلك تحقق من صحة البيانات");
              self.show_error = true;
            }
          })
          .fail(function(data) {
            self.show_error = true;
            self.errors.push("هناك خطأ في الاتصال");
            console.log(data);
          });
      } else {
        this.email_state = false;
        this.errors.push("من فضلك تحقق من صحة البيانات");
        this.show_error = true;
      }
    }
  }
};
</script>
<style scoped>
body {
  font-family: "Lato", sans-serif;
}
</style> 