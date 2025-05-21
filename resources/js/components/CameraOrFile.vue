<template>
  <div>
    <v-btn-toggle v-model="mode" mandatory class="mb-4" density="compact" rounded>
      <v-btn value="file" variant="outlined" color="primary">رفع ملف</v-btn>
      <v-btn value="camera" variant="outlined" color="primary">كاميرا</v-btn>
    </v-btn-toggle>

    <div v-if="mode === 'file'">
      <v-file-input
        v-model="fileInput"
        accept="image/*"
        label="اختر صورة"
        prepend-icon="mdi-file-image"
        @change="onFileChange"
        clearable
        outlined
        dense
      />
    </div>

    <div v-else>
      <v-card elevation="2" class="pa-3" max-width="360">
        <video
          ref="video"
          autoplay
          playsinline
          width="320"
          height="240"
          style="border-radius: 8px; background: #000;"
        />
        <v-btn
          class="mt-3"
          color="primary"
          block
          @click="capture"
          :disabled="!cameraReady"
        >
          التقاط صورة
        </v-btn>
        <v-img
          v-if="photoData"
          :src="photoData"
          alt="الصورة الملتقطة"
          max-width="320"
          class="mt-3"
          contain
          rounded
        />
      </v-card>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import { useDisplay } from "vuetify";

const emit = defineEmits(["update:file"]);

const mode = ref("file");
const video = ref(null);
const photoData = ref(null);
const fileInput = ref(null);
let stream = null;
const cameraReady = ref(false);

onMounted(() => {
  if (mode.value === "camera") {
    startCamera();
  }
});

watch(mode, (newMode) => {
  if (newMode === "camera") {
    startCamera();
  } else {
    stopCamera();
    photoData.value = null;
  }
});

function startCamera() {
  navigator.mediaDevices
    .getUserMedia({ video: { facingMode: "environment" } })
    .then((s) => {
      stream = s;
      video.value.srcObject = stream;
      cameraReady.value = true;
    })
    .catch((err) => {
      console.error("تعذر الوصول للكاميرا:", err);
      cameraReady.value = false;
    });
}

function stopCamera() {
  if (stream) {
    stream.getTracks().forEach((track) => track.stop());
    stream = null;
  }
  cameraReady.value = false;
}

onBeforeUnmount(() => {
  stopCamera();
});

function capture() {
  const canvas = document.createElement("canvas");
  canvas.width = video.value.videoWidth || 320;
  canvas.height = video.value.videoHeight || 240;
  const ctx = canvas.getContext("2d");
  ctx.drawImage(video.value, 0, 0, canvas.width, canvas.height);
  photoData.value = canvas.toDataURL("image/png");
  canvas.toBlob((blob) => {
    const file = new File([blob], "photo.png", { type: "image/png" });
    emit("update:file", file);
  });
}

function onFileChange() {
  emit("update:file", fileInput.value);
}
</script>

<style scoped>
/* تحسين التباعد */
.v-btn-toggle {
  gap: 12px;
}
</style>
