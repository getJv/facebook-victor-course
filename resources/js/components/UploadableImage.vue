<template>
  <div>
    <img :src="userImage.data.attributes.path" :alt="altText" ref="userImage" :class="classes" />
  </div>
</template>

<script>
import Dropzone from "dropzone";
export default {
  name: "UploadableImage",
  props: [
    "altText",
    "classes",
    "userImage",
    "imageWidth",
    "imageHeight",
    "location"
  ],
  mounted() {
    if (
      this.$store.getters.authUser.data.user_id == this.$route.params.userId
    ) {
      this.dropzone = new Dropzone(this.$refs.userImage, this.settings);
    }
  },
  computed: {
    settings() {
      return {
        paramName: "image",
        url: "/api/user-images",
        acceptedFiles: "image/*",
        params: {
          width: this.imageWidth,
          height: this.imageHeight,
          location: this.location
        },
        headers: {
          "X-CSRF-TOKEN": document.head.querySelector("meta[name=csrf-token]")
            .content
        },
        success: (e, res) => {
          this.uploadedImage = res;
          this.$store.dispatch("fetchAuthUser");
          this.$store.dispatch("fetchUser", this.$route.params.userId);
          this.$store.dispatch("fetchUserPosts", this.$route.params.userId);
        }
      };
    }
  },
  data() {
    return {
      dropzone: null
    };
  }
};
</script>

<style>
</style>
