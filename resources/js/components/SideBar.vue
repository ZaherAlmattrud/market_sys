<template>
    <v-card>
      <v-layout>
        <v-navigation-drawer
          location="right"
          permanent
        >
          <template v-slot:prepend>
            <v-list-item
              lines="two"
              prepend-avatar="https://randomuser.me/api/portraits/women/81.jpg"
              subtitle="Logged in"
              title="Jane Smith"
            ></v-list-item>
          </template>
  
          <v-divider></v-divider>
  
          <v-list density="compact" nav >
            <RouterLink to="/"> <v-list-item  prepend-icon="mdi-list-box-outline" title="الرئيسية" value="home"></v-list-item></RouterLink>
           
            <RouterLink to="/"> <v-list-item prepend-icon="mdi-pipe-valve" title="الصحية" value="account"></v-list-item></RouterLink>
            <RouterLink to="/"> <v-list-item prepend-icon="mdi-account-box-outline" title="الزبائن" value="users"></v-list-item></RouterLink>
                <RouterLink to="/"> <v-list-item prepend-icon="mdi-briefcase-account-outline" title="الحسابات" value="accounts"></v-list-item></RouterLink>
                    <RouterLink to="/"> <v-list-item prepend-icon="mdi-account-group-outline" title="الموردين" value="supplers"></v-list-item></RouterLink>
                        <RouterLink to="/"> <v-list-item prepend-icon="mdi-invoice-text-multiple-outline" title="الفواتير" value="invoices"></v-list-item></RouterLink>
                            <RouterLink to="/"> <v-list-item prepend-icon="mdi-map-marker-multiple-outline" title="المناطق" value="areas"></v-list-item></RouterLink>
                                <RouterLink to="/"> <v-list-item prepend-icon="mdi-shape-plus-outline" title="الأصناف" value="category"></v-list-item></RouterLink>
                                    <RouterLink to="/"> <v-list-item prepend-icon="mdi-account-lock-outline" title="الخروج" value="logOut"></v-list-item></RouterLink>
         
          </v-list>
        </v-navigation-drawer>
        <v-main style="height: 480px">
        
            <RouterView /></v-main>
      </v-layout>
    </v-card>
  </template>