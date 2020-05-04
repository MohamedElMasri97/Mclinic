
<template>
  <div id="login_root">
    <hdr></hdr>
    <b-container>
      <b-row align-h="center" class="mt-5">
        <b-col cols="14">
          <b-card >
            <h3 class="mb-4">تسجيل</h3>
            <div>
              <b-form>
                <b-form-group label="كيف تود التسجيل">
                  <b-form-radio-group
                    id="btn-radios-3"
                    class="width"
                    button-variant="outline-primary"
                    v-model="$store.state.new_what"
                    :options="[
                     { text:'مستخدم',  value:'newpatient' },
                       {text:'طبيب',  value:'newdoctor' },
                       {text:'صيدلية',  value:'newpharmacy' },
                       {text:'معمل تحاليل',  value:'newlab' }
                    ]"
                    buttons
                    stacked
                    name="radio-btn-stacked"
                  ></b-form-radio-group>
                </b-form-group>                
                <b-button variant="primary" @click="check">المتابعة</b-button>
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
import hdr from "./../login_hdr";
export default {
  components: {
    hdr
  },
  data() {
    return {
      type: ""
    };
  },
  methods: {
    check() {
      if (this.$store.state.new_what) {
        this.$router.push({ name: this.$store.state.new_what });
      }
    }
  },
  computed: {}
};
</script>
<style scoped>
body {
  text-decoration: none;
}
router-link {
  color: white;
}
.mt {
  margin-top: 1rem;
}
.width {
  margin: 1rem auto;
  width: 300px;
}
</style>