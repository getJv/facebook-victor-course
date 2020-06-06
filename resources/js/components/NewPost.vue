<template>
  <div class="bg-white rounded shadow w-2/3 p-4">
    <div class="flex justify-between items-center">
      <div>
        <img
          :src="authUser.data.attributes.profile_image.data.attributes.path"
          alt="profile image for user"
          class="w-8 h-8 object-cover rounded-full"
        />
      </div>
      <div class="flex-1 flex mx-4">
        <input
          type="text"
          v-model="postMessage"
          name="body"
          class="w-full pl-4 h-8 bg-gray-200 rounded-full focus:outline-none focus:shadow-outline text-sm"
          placeholder="Add a post"
        />

        <transition name="fade">
          <button
            v-if="postMessage"
            @click="postHandler"
            class="bg-gray-200 ml-2 px-3 py-1 rounded-full"
          >Post</button>
        </transition>
      </div>
      <div>
        <!-- dz-clikable it is a special css class to use with dropzone -->
        <button
          ref="postImage"
          class="dz-clikable flex justify-center items-center rounded-full w-10 h-10 bg-gray-200"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            class="dz-clikable fill-current w-5 h-5"
          >
            <path
              d="M21.8 4H2.2c-.2 0-.3.2-.3.3v15.3c0 .3.1.4.3.4h19.6c.2 0 .3-.1.3-.3V4.3c0-.1-.1-.3-.3-.3zm-1.6 13.4l-4.4-4.6c0-.1-.1-.1-.2 0l-3.1 2.7-3.9-4.8h-.1s-.1 0-.1.1L3.8 17V6h16.4v11.4zm-4.9-6.8c.9 0 1.6-.7 1.6-1.6 0-.9-.7-1.6-1.6-1.6-.9 0-1.6.7-1.6 1.6.1.9.8 1.6 1.6 1.6z"
            />
          </svg>
        </button>
      </div>
    </div>
    <!-- preview image post -->
    <div class="dropzone-previews">
      <div id="dz-template" class="hidden">
        <div class="dz-preview dz-file-preview mt-4">
          <div class="dz-details">
            <img data-dz-thumbnail class="w-32 h-32" />
            <button data-dz-remove class="text-xs">REMOVE</button>
          </div>
          <div class="dz-progress">
            <span class="dz-upload" data-dz-upload></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import _ from "lodash";
import Dropzone from "dropzone";
import { mapGetters } from "vuex";
export default {
  name: "NewPost",

  methods: {
    postHandler() {
      if (this.dropzone.getAcceptedFiles().length) {
        this.dropzone.processQueue();
      } else {
        this.$store.dispatch("postMessage");
      }
      this.$store.commit("updateMessage", "");
    }
  },
  data() {
    return {
      dropzone: null
    };
  },
  mounted() {
    this.dropzone = new Dropzone(this.$refs.postImage, this.settings);
  },
  computed: {
    settings() {
      return {
        paramName: "image",
        url: "/api/posts",
        acceptedFiles: "image/*",
        clickable: ".dz-clikable", // active the class dz-clikable
        autoProcessQueue: false,
        maxFiles: 1,
        previewsContainer: ".dropzone-previews",
        previewTemplate: document.querySelector("#dz-template").innerHTML,
        params: {
          width: 1000,
          height: 1000
        },
        sending: (file, xhr, formData) => {
          formData.append("body", this.$store.getters.postMessage);
        },
        headers: {
          "X-CSRF-TOKEN": document.head.querySelector("meta[name=csrf-token]")
            .content
        },
        success: (e, res) => {
          this.dropzone.removeAllFiles();
          this.$store.commit("pushPost", res);
        },
        maxfilesexceeded: files => {
          this.dropzone.removeAllFiles();
          this.dropzone.addFile(file);
        }
      };
    },
    ...mapGetters({
      authUser: "authUser"
    }),
    postMessage: {
      get() {
        return this.$store.getters.postMessage;
      },
      set: _.debounce(function(postMessage) {
        this.$store.commit("updateMessage", postMessage);
      }, 300)
    }
  }
};
</script>
<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
