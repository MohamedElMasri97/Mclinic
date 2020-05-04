<template>
  <div id="register_root">
    <b-container class="mt">
      <b-row align-h="center" class="mt-7">
        <b-col cols="14">
          <b-card class="p-3">
            <h3 class="mb-4">تسجيل الخروج</h3>
            <div>
              <b-alert v-model="show_error" variant="warning" dismissible>{{error_msg}}</b-alert>
              <b-form>
                <b-form-group label="هل انت متاكد انك تود تسجيل الخروج">
                  <b-form-radio v-model="type" value="cancel">رجوع</b-form-radio>
                  <b-form-radio v-model="type" value="logout">تسجيل الخروج</b-form-radio>
                </b-form-group>
                <b-button variant="primary" @click="check">المتابعة</b-button>
              </b-form>
            </div>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>

<script>
import c from '../../../common/common.js'
import $ from "jquery";
export default {
  data() {
    return {
      type: "",
      error_msg: "",
      show_error: false
    };
  },
  created(){
    console.log('you are in logout vue comp');
  },
  methods: {
    check() {
      var self = this;
      console.log(self.type)
      if (self.type == "logout") {
        console.log('login out...');
        c.checkme();
        $.ajax({
          pass:self.$store.state.phrase,
          method: "POST",
          url: self.$store.state.url,
          data: {
            action: "logout",
            email: localStorage.email
          }
        })
          .always(function(data) {
            self.show_error = false;
            self.error_msg = "";
          })
          .done(function(data) {
            localStorage.clear();
            self.$router.push({ name: "login" });
          })
          .fail(function(data) {
            console.log(data);
            self.error_msg = "عفوا حدث خطأ حاول مرة اخرى لاحقا";
            self.show_error = true;
          });
      } else {
        self.$router.push({ name: "root" });
      }
    }
  },
  computed: {}
};
</script>
<style scoped>
#register_root{
  margin:15px;
}
</style>