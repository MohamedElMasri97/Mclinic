<template>
  <div>
    <b-navbar toggleable="lg" type="dark" class="nav-background">
      <b-navbar-brand href="#">
        <span class="block">Mclinic</span>
        <div class="block"> <small>مرحبا </small></div>
        <div class="block"><small>{{$store.state.username}}</small></div>
      </b-navbar-brand>

      <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

      <b-collapse id="nav-collapse" is-nav>
        <b-navbar-nav class="ml-auto" v-if="$store.state.usertype == '0' ">
          <b-nav-item>
            <router-link :to="{name:'patient_request'}" class="no">اجراء طلب</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'showrequests'}" class="no">الطلبات</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'showappointments'}" class="no">المواعيد و الحجوزات</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'logout'}" class="no">تسجيل الخروج</router-link>
          </b-nav-item>
          <span class="spacb"></span>
        </b-navbar-nav>


        <b-navbar-nav class="ml-auto" v-if="$store.state.usertype == '1' ">
          <b-nav-item>
            <router-link :to="{name:'doctor_trending'}" class="no">طلبات المستخدمين</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'doctor_offers'}" class="no">عروضك</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'doctor_appointments'}" class="no">المواعيد و الحجوزات</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'logout'}" class="no">تسجيل الخروج</router-link>
          </b-nav-item>
          <span class="spacb"></span>
        </b-navbar-nav>


        <b-navbar-nav class="ml-auto" v-if="$store.state.usertype == '2' ">
          <b-nav-item>
            <router-link :to="{name:'pharmacy_trending'}" class="no">طلبات المستخدمين</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'pharmacy_offers'}" class="no">عروضك</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'pharmacy_appointments'}" class="no">المواعيد و الحجوزات</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'logout'}" class="no">تسجيل الخروج</router-link>
          </b-nav-item>
          <span class="spacb"></span>
        </b-navbar-nav>


        <b-navbar-nav class="ml-auto" v-if="$store.state.usertype == '3' ">
          <b-nav-item>
            <router-link :to="{name:'lab_trending'}" class="no">طلبات المستخدمين</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'lab_offers'}" class="no">عروضك</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'lab_appointments'}" class="no">المواعيد و الحجوزات</router-link>
          </b-nav-item>
          <span class="spaceb"></span>
          <b-nav-item>
            <router-link :to="{name:'logout'}" class="no">تسجيل الخروج</router-link>
          </b-nav-item>
          <span class="spacb"></span>
        </b-navbar-nav>
      </b-collapse>
    </b-navbar>
  </div>
</template>
<script>
export default {};
</script>
<style scoped>
.block{
  display: inline-block;
}
.spaceb {
  direction: rtl;
  width: 2rem;
}
.space {
  direction: rtl;
  width: 6rem;
}
.no {
  padding: 1rem;
  direction: rtl;
  text-decoration: none;
  color: white;
}
.nav-background {
  background-color: rgb(27, 27, 27);
}
</style>