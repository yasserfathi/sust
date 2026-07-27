<template>
  <v-locale-provider  cols="12" rtl>
    <p class="text-center text-h1" style="margin-top: 15%">
      404
    </p>
    <p class="text-center text-h5  mt-2 mb-8">
      ربما تمت إزالة الصفحة التي تبحث عنها أو تم تغيير اسمها أو أنها غير متاحة مؤقتًا.
    </p>
    <p class="text-center">
      <router-link to="/">
        <v-btn ripple rounded="xl" size="large" prepend-icon="mdi-home" variant="elevated" color="#f04146">الصفحة الرئيسية</v-btn>
      </router-link>
    </p>
  </v-locale-provider>
</template>
