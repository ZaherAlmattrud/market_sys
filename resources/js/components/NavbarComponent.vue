<template>

    <v-app-bar app color="gray">
        <template v-slot:prepend>
            <v-app-bar-nav-icon></v-app-bar-nav-icon>
        </template>

        <!-- <v-app-bar-title>مركز المطرود التجاري</v-app-bar-title> -->

        <template v-slot:append>
            <v-row justify="center" no-gutters>
                <v-btn v-for="link in links" :key="link" class="mx-2" color="white" rounded="xl" variant="text">
                    {{ link }}
                </v-btn>

            </v-row>
        </template>
    </v-app-bar>


</template>



<script>

export default {
    data: () => ({
        links: [


            'دليل توتال',
            ' دليل القشط',
            'دليل الرومانات',
            'دليل الغاطسات',
            'دليل الكهرباء',
            'دليل التمديدات الصحية',
            'دليل البراغي',
            'معدلات ليبرا',

        ],
    }),


}
</script>