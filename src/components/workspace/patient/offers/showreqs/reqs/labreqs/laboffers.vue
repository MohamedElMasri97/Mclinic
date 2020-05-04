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
      <h4>العروض على هذا الطلب</h4>
      <div>
        <b-button class="backbtn" @click="$event.preventDefault;refresh(element)">Refresh</b-button>
        <b-button class="backbtn" @click="$event.preventDefault;del(element)">حذف الطلب</b-button>
        <b-button class="backbtn" @click="$event.preventDefault;back()">رجوع</b-button>
      </div>
    </div>
    <div v-if="showreq" class="header">
      <p>نص الطلب: {{req.note}}</p>
      <hr class="hr" />
      <p>Your reason: {{req.note}}</p>
      <hr class="hr" />
      <p>مكان الطلب: {{req.np}}, {{$store.state.citymap[req.city]}}</p>
      <hr class="hr" />
      <small>وقت الطلب: {{req.ts}}</small>
    </div>
    <hr class="hr" />
    <div v-if="showoffers" class="header">
      <b-card-group deck style="width:100%;margin:0 auto;">
        <b-row v-for="element in offers" :key="element.id" style="width:100%;margin:10px;">
          <b-card class="cardcolor">
            <b-card-text>{{short(element.note)}}</b-card-text>
            <template v-slot:footer>
              <p>هذا العرض مقدم من الطبيب {{element.name}}, متواجد في  {{element.np}}, {{$store.state.citymap[element.city]}}.</p>
              <small>الوقت الذي ارسل فيه العرض: {{element.ts}}</small>
              <hr class="hr" />
              <b-button class="backbtn" @click="$event.preventDefault;details(element)">التفاصيل</b-button>
            </template>
          </b-card>
          <hr class="hr" />
        </b-row>
      </b-card-group>
    </div>
  </div>
</template>
<script>
import c from "../../../../../../../common/common.js";
import $ from "jquery";
export default {
  data() {
    return {
      show_error: false,
      errors: [],
      offers: [],
      req: {},
      showreq: false,
      state: "warning",
      showoffers: false
    };
  },
  created() {
    var self = this;
    if (!self.$route.params.req_id) {
      self.$router.push({ name: "labreqs" });
    } else {
      self.refresh();
    }
  },
  methods: {
    details(element) {
        var self = this;
        self.$router.push({
        name: "labdetailedoffer",
        params: {
            offerid: element.oid,
            req_id: self.$route.params.req_id,
            providerid: element.id
        }
        });
    },
    del() {
        if (confirm("هل انت متاكد من رغبتك في حذف هذا الطلب")) {
        var self = this;
        self.showreq = false;
        self.showoffers = false;
        self.show_error = false;
        self.errors = [];
        c.checkme();
        $.ajax({
            method: "POST",
            url: self.$store.state.url,
            data: {
            pass: self.$store.state.phrase,
            action: "del_req",
            req_id: self.$route.params.req_id,
            pid:self.req.fuserid,
            type: "3"
            }
        }).done(function(data) {
            var results = [];
            try {
            results = JSON.parse(data);
            console.log("successfull query and results is " + results);
            if (results[0]) {
                self.$router.push({
                name: "labreqs",
                params: {
                    alert: {
                    state: "success",
                    msg: "تم ازالة عرضك بنجاح"
                    }
                }
                });
            } else {
                self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
                self.show_error = true;
            }
            } catch (err) {
            console.log(
                "fail query and error is " + err + " and data is " + data
            );
            self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
            self.show_error = true;
            }
        })
        .fail(function(data) {
          console.log("successfull query and data is " + data);
          self.show_error = true;
          self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
        });
        }
    },
    short(element) {
      return c.short(element);
    },
    back() {
      this.$router.push({ name: "labreqs" });
    },
    refresh() {
      var self = this;
      self.showreq = false;
      self.showoffers = false;
      self.show_error = false;
      self.state = "warning";
      self.errors = [];
      c.checkme();
      $.ajax({
        method: "POST",
        url: self.$store.state.url,
        data: {
          pass: self.$store.state.phrase,
          action: "fetch_patient_offers_from_lab",
          req_id: self.$route.params.req_id
        }
      })
        .done(function(data) {
          var results = [];
          try {
            results = JSON.parse(data);
            console.log("successfull query and results is " + results);
            console.log(results);
            if (results[0] == "1") {
              self.offers = results[2];
              self.req = results[1];
              self.showreq = true;
              self.showoffers = true;
            } else if (results[0] == "2") {
              self.req = results[1];
              self.showreq = true;
              self.errors = ["ليس لديك اي عروض على هذا الطلب"];
              self.show_error = true;
              self.state = "warning";
            } else {
              self.back();
            }
          } catch (err) {
            console.log(
              "successfull query and error is " + err + " and data is " + data
            );
            self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
            self.show_error = true;
          }
        })
        .fail(function(data) {
          console.log("successfull query and data is " + data);
          self.show_error = true;
          self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
        });
    }
  },
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
.hr {
  margin: 10px auto;
}
#root{
  margin: 10px;
}
.cardcolor {
  background:rgb(169, 224, 255);
}
</style>
