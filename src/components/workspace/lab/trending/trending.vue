<template>
  <div id="root">
    <div>
      <b-form inline>
        <label id="city">city : </label>
        <b-form-select
          id="inlinecity"
          v-model="city"
          :options="$store.state.cities"
          size="sm"
          class="mb-2 mr-sm-2 mb-sm-0"
        ></b-form-select>
        <b-button variant="primary" style="background:rgb(130,130,130)" size="sm" @click="fetch">Search</b-button>
      </b-form>
    </div>
    <b-alert v-model="show_error" :variant="state" class="alert" dismissible>
      <ul>
        <li v-for="error in errors" :key="error">{{error}}</li>
      </ul>
    </b-alert>
    <hr />
    <b-card-group deck>
      <b-row v-for="element in elements" :key="element.req_id" class="mrg">
        <b-card class="cardcolor">
          <b-card-text>
            {{element.note}}
          </b-card-text>
          <template v-slot:footer>
            <p>اسم صاحب الطلب {{element.name}}, متواجد في  {{element.np}}, {{$store.state.citymap[element.city]}}</p>
            <p>وقت الطلب:  {{element.ts}}</p>
            <hr />
            <b-button variant="primary" @click="offer(element)">ارسال عرض</b-button>
          </template>
        </b-card>
      </b-row>
    </b-card-group>
  </div>
</template>
<script>
import c from '../../../../common/common.js';
import $ from "jquery";
export default {
  data() {
    return {
      show_error: false,
      state: "success",
      errors: [],
      city: "",
      elements: [],
      limit: 100
    };
  },
  created() {
    var self = this;
    if (this.$store.state.type == "success") {
      self.show_error = true;
      self.state = "success";
      self.errors = self.$store.state.msg;
      self.$store.state.type = "";
    }
    c.checkme();
    $.ajax({
      method: "POST",
      url: self.$store.state.url,
      data: {
        pass: self.$store.state.phrase,
        action: "fetch_patients_forlab",
        city: "all",
        limit: self.limit
      }
    })
      .done(function(data) {
        var results = [];
        try {
          results = JSON.parse(data);
          if (results[0]) {
            console.log(results);
            self.elements = results[1];
          } else {
            console.log("error while fetching patients ,data: " + data);
            self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
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
  methods: {
    fetch() {
      var self = this;
      self.errors = [];
      self.state = "warning";
      self.show_error = false;
      var results = [];
      if (self.city) {
        c.checkme();
        $.ajax({
          method: "POST",
          url: self.$store.state.url,
          data: {
            pass: self.$store.state.phrase,
            action: "fetch_patients_lab",
            city: self.city,
            limit: self.limit
          }
        })
          .done(function(data) {
            var results = [];
            try {
              results = JSON.parse(data);
              if (results[0]) {
                self.elements = results[1];
              } else {
                console.log("error while fetching patients ,data: " + data);
                self.errors = ["عفوا حدث خطأ حاول مرة اخرى لاحقا"];
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
      }
    },
    offer(element) {
      console.log(element);
      this.$store.state.lab_offer_element = element;
      this.$router.push({ name: "lab_offer" });
    }
  }
};
</script>
<style scoped>
#city{
    margin-right:5px;
  }
#root {
  padding: 1rem;
  margin: 0 auto;
}
.mrg{
  margin: 6px auto;
  width:100%;
}
.cardcolor{
  color: white;
  background:rgba(57, 91, 163, 0.7);
}



</style>