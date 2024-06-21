<template>
  <div class="flex h-screen">
    <div class="sidebar w-100 bg-[#011c3e]" style="min-width: 500px">
      <component :is="'v-btn'" v-if="false"></component>
      <div class="flex-col row-span-3 m-2 bg-[#002046]">
        <div class="flex p-5">
          <ul class="font-bold flex-1 text-left text-white text-2xl">
            Messages
          </ul>
          <ul class="font-bold flex-1 text-right">
            <font-awesome-icon :icon="['fas', 'ellipsis-v']" class="text-white" />
          </ul>
        </div>
        <div class="pb-7 mx-2">
          <div class="input-group">
            <input type="text" placeholder="Search in the list" class="input-field" @keyup.enter="onEnterPhone" />
          </div>
        </div>
      </div>
      <div class="row-span-9 overflow-y-auto h-[calc(100vh-165px)]">
        <ul>
          <div v-for="messageHistory in messageHistories" :key="messageHistory.id"
            @click="showMessageHistory(messageHistory.phoneNum, messageHistory.lastword, messageHistory.history); IsSelectedItemID = messageHistory.id;"
            :class="{ 'bg-orange-600': IsSelectedItemID == messageHistory.id, 'hover:bg-orange-600': IsSelectedItemID != messageHistory.id }"
            class="m-2 rounded-lg bg-[#002046] text-white hover:bg-orange-600 cursor-pointer hover:text-white relative"
            @mouseover="hover = messageHistory.id" @mouseleave="hover = null">
            <div class="flex justify-between items-center w-100 call-list cursor-pointer">
              <div class="flex items-center">
                <div class="icon-bg-call m-3"
                  :style="{ borderRadius: '80%', backgroundColor: IsSelectedItemID == messageHistory.id ? 'white' : 'orangered' }">
                  <font-awesome-icon icon="user" class="font-semibold my-3 icon-style-call "
                    :class="{ 'text-orange-600': IsSelectedItemID == messageHistory.id }"
                    :style="{ borderRadius: '80%', backgroundColor: IsSelectedItemID == messageHistory.id ? 'white' : 'orangered' }"
                    style="width: 3rem; height: 1.5rem" />
                </div>
                <div class="flex flex-col justify-center ">
                  <p class="font-bold text-left">
                    {{ messageHistory.phoneNum }}
                  </p>
                  <p class="text-sm text-left">{{ messageHistory.lastword }}</p>
                </div>
              </div>
              <font-awesome-icon icon="trash" class="mr-3 cursor-pointer" v-if="hover === messageHistory.id"
                @click.stop="deletePhoneNumber(messageHistory.id)" />
            </div>
          </div>
        </ul>
      </div>
    </div>
    <div v-if="showDetail" class="w-full flex flex-col bg-[#ffffff] h-[calc(100vh)]">
      <div class="h-15 flex p-3 pb-0 ml-2 mt-5 mb-2">
        <div class="icon-bg-call-2 m-2" style="background-color: orangered; border-radius: 80%">
          <font-awesome-icon icon="user" class="font-semibold my-3 icon-style-call text-white"
            style="color: white; background-color: orangered; border-radius: 80%"
            :style="{ width: '3rem', height: '1.5rem' }" />
        </div>
        <div class="flex flex-col justify-center">
          <h1 class=" ml-3 text-left text-black">{{ phoneNumber }}</h1>
        </div>
        <div class="flex-grow">
          <div class="input-group ml-8 mt-3">
            <input type="text" placeholder="Search messages" class="input-field border-2 border-slate-500 text-black" />
          </div>
        </div>
        <div class="flex items-center">
          <div class="icon-bg-call-2 m-2" style="background-color: orangered; border-radius: 80%">
            <font-awesome-icon :icon="['fas', 'video']" class="font-semibold my-3 icon-style-call text-white"
              style="color: white; background-color: orangered; border-radius: 80%"
              :style="{ width: '3rem', height: '1.5rem' }" />
          </div>
          <div class="icon-bg-call-2 m-1 ml-3" style="background-color: orangered; border-radius: 80%">
            <font-awesome-icon :icon="['fas', 'phone']" class="font-semibold my-3 icon-style-call text-white"
              style="color: white; background-color: orangered; border-radius: 80%"
              :style="{ width: '3rem', height: '1.5rem' }" />
          </div>
        </div>
      </div>
      <div ref="chatContainer" class="overflow-y-auto grow border-inherit"
        style="background-image: url('./assets/WhatsApp_background.jpg'); background-size: cover; background-repeat: no-repeat;">
        <div v-for="it in messageData" :key="it.message_id" class="my-1" @mouseover="showIcons = it.id"
          @mouseleave="showIcons = null">
          <div v-if="it.user === '1'" class="flex justify-end mr-20">
            <div v-if="it.value !== null" class="w-fit p-2 text-sm bg-green-200"
              :style="{ borderRadius: it.type === 'message' ? '10px 10px 0 10px' : '0 10px 10px 10px' }">
              <div v-if="it.type === 'image'">
                <img :src="it.value" style="width: 15vw; height: auto;" />
              </div>
              <div v-else-if="it.type === 'video'">
                <video :src="it.value" controls style="max-width: 20vw; height: auto;"></video>
              </div>
              <div v-else-if="it.type === 'audio'">
                <audio :src="it.value" controls style="max-width: 15vw; height: auto;"></audio>
              </div>
              <div v-else-if="it.type === 'file'">
                <a :href="it.value" download style="color: white; font-size: 20px;">{{ it.value }}</a>
              </div>
              <div v-else class="flex justify-start ml-3 mr-20">
                <span :style="{ fontSize: '15px', display: it.value.length > 100 ? 'block' : 'inline' }">{{
              it.value }}</span>
              </div>
              <!-- Trash Can Icon -->
              <!-- Message Status and Time -->
              <div :class="{ 'mt-0': it.type === 'message', 'mt-2': it.type !== 'message' }"
                class="flex justify-between">
                <span style="display: inline-flex; align-items: center;">
                  <font-awesome-icon icon="trash" class="mr-3 text-black cursor-pointer" style="margin-right: 5px;"
                    v-if="showIcons === it.id" @click.stop="deleteMessage(it.id)" />
                </span>
                <span style="display: inline-flex; align-items: center;">
                  <span style="margin-left: 5px; font-size: 11px;">{{ it.time }}</span>
                  <font-awesome-icon icon="fa-solid fa-check" class="text-black mr-2"
                    style="margin-left: 5px; font-size: 11px;" />
                </span>
              </div>
            </div>
          </div>
          <div v-else class="flex justify-start ml-20">
            <div v-if="it.value !== null" class="w-fit p-2 text-sm"
              :style="{ backgroundColor: it.type === 'message' ? '#ffffff' : '#ffffff', borderRadius: it.type === 'message' ? '10px 10px 0 10px' : '0 10px 10px 10px' }">
              <div v-if="it.type === 'image'">
                <img :src="it.value" class="media-image" style="width: 15vw; height: auto;" />
              </div>
              <div v-else-if="it.type === 'video'">
                <video :src="it.value" controls style="max-width: 20vw; height: auto;"></video>
              </div>
              <div v-else-if="it.type === 'audio'">
                <audio :src="it.value" controls style="max-width: 15vw; height: auto;"></audio>
              </div>
              <div v-else-if="it.type === 'file'">
                <a :href="it.value" download style="background-color: #0f82ff; color: white; font-size: 20px;">{{
              it.value }}</a>
              </div>
              <div v-else class="flex justify-start ml-2 mr-20">
                <span
                  :style="{ color: 'black', fontSize: '15px', display: it.value.length > 100 ? 'block' : 'inline' }">{{
              it.value }}</span>
              </div>
              <!-- Message Time -->
              <div :class="{ 'mt-0': it.type === 'message', 'mt-2': it.type !== 'message' }"
                class="flex justify-between">
                <span style="display: inline-flex; align-items: center;">
                  <font-awesome-icon icon="trash" class="mr-3 text-black cursor-pointer" style="margin-right: 5px;"
                    v-if="showIcons === it.id" @click.stop="deleteMessage(it.id)" />
                  <font-awesome-icon icon="download" v-if="showIcons === it.id" class="mr-3 text-black cursor-pointer download-icon" @click="downloadMedia(it.value)" />
                </span>
                <span style="display: inline-flex; align-items: center;">
                  <span style="margin-left: 5px; font-size: 11px;">{{ it.time }}</span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="input-container" style="height: 10vh; position: relative;">
        <div v-if="isVisibleMenu == true"
          class="flex flex-col absolute gap-2 w-[220px] h-[45px] overflow-y-auto bottom-[60px] left-1 border shadow-lg rounded-full text-black bg-white">
          <div
            class="w-full py-1 pl-6 pt-1 pr-6 flex justify-start items-center rounded-full hover:bg-orange-600 cursor-pointer">
            <button :loading="isSelecting" @click="handleFileImport"
              class="icon-button rounded-full hover:bg-orange-600 hover:text-white mr-4 p-2">
              <font-awesome-icon icon="user" class="ml-4 mr-3" />
              Media Files
            </button>
            <input ref="uploader" type="file" style="display: none" @change="onFileChanged" />
          </div>
        </div>
        <button @click="toggleMenu" class="icon-button rounded-full hover:bg-white mr-2 p-2"
          :class="{ 'bg-gray-200': isVisibleMenu }">
          <v-btn class="icon-button " :class="{ 'rotate1': isVisibleMenu, 'rotate2': !isVisibleMenu }"
            :is-visible-menu="isVisibleMenu" />
        </button>
        <input type="text" v-model="inputMessage" @keypress="onEnter" placeholder="Your input"
          class="input-field border-2 border-slate-500 mt-2 mb-2" />
        <v-btn class="icon-button">
          <font-awesome-icon :icon="['fas', 'volume-up']" class="text-gray w-6 h-6 ml-3" />
        </v-btn>
      </div>
    </div>
  </div>
</template>

<script setup>

import 'tailwindcss/tailwind.css';
import axios from "axios";
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

import { ref, onMounted, watchEffect } from 'vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCheck, faCheckDouble } from '@fortawesome/free-solid-svg-icons';


library.add(fas);
library.add(faCheck, faCheckDouble);

const id = ref(0);
const message_id = ref(0);
const showDetail = ref(false);
const phoneNumber = ref("");
const timeStamp = ref("");
const histories = ref([]);

const IsSelectedItemID = ref(null);
const inputMessage = ref("");
const isVisibleMenu = ref(false);
const isSelecting = ref(false);

const messageHistories = ref([]);
const messageData = ref([]);

const initphoneNumber = ref("");

window.Pusher = Pusher;

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
  wsHost: window.location.hostname,
  wsPort: 6001,
  wssPort: 6001,
  forceTLS: false,
  disableStats: true,

});

const chatContainer = ref(null);

// Function to scroll to the bottom
const scrollToBottom = () => {
  if (chatContainer.value) {
    chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
  } else {
    console.log('chatContainer is null or scrollHeight is not available');
  }
};

// Watch for changes in the messages array and scroll to bottom
watchEffect(() => {
  scrollToBottom();
});

onMounted(() => {
  axios
    .get('/get-message')
    .then(response => {
      initphoneNumber.value = response.data;
      for (let i = 0; i < initphoneNumber.value.length; i++) {
        initData(initphoneNumber.value[i].phonnumber);
      }
    })
    .catch(error => {
      console.error('Error fetching message:', error);
    });

  scrollToBottom();

  window.Echo.channel('chat').listen('MessageSent', (e) => {
    messageData.value.push(e.message);
    chatContainer.value = document.getElementById('chatContainer');
    scrollToBottom();
    console.log("messageSent >>>>>>>", e.message);
  })
});

function initData(phonenumber_value) {
  axios
    .post('/get-message', { phonenumber: phonenumber_value })
    .then(response => {
      messageData.value = response.data;
      console.log("messageData", messageData.value[0]);
      console.log("id", messageData.value[messageData.value.length - 1].value);
      let currentValue = messageData.value[messageData.value.length - 1].value;
      // Check if the current value exceeds 16 characters
      if (currentValue.length > 16) {
        // Limit the length of the string to 16 characters
        currentValue = currentValue.substring(0, 23);
      }
      messageHistories.value.push({
        id: id.value++,
        phoneNum: messageData.value[0].phonnumber,
        lastword: currentValue
      });
    })
    .catch(error => {
      console.error('Error fetching message:', error);
    });
}

function RealtimeData(eventData) {
  const eventPhoneNumber = eventData.message['phonnumber'];
  const eventtime = eventData.message['time'];
  const eventid = eventData.message['id'];
  console.log("eventPhoneNumber", eventPhoneNumber);
  const phoneNumberExist = messageHistories.value.find(history => history.phoneNum === eventPhoneNumber);
  console.log("phoneNumberExist", phoneNumberExist["id"]);

}

const hover = ref(null);
// Function to delete a phone number
function deletePhoneNumber(id) {
  // Find the index of the message history with the given id
  const index = messageHistories.value.findIndex(history => history.id === id);
  console.log("index", index);
  if (index !== -1) {
    // Get the phone number associated with the message history
    const delete_phoneNum = messageHistories.value[index].phoneNum;
    // Remove the message history from the array
    messageHistories.value.splice(index, 1);
    // Make an API call to delete the phone number from the backend
    axios.post('/delete-phone', { phonenumber: delete_phoneNum })
      .then(response => {
        console.log("Phone number deleted successfully");
        if (index != "0") {
          reloadingData(messageHistories.value[index - 1].phoneNum);
        } else {
          reloadingData(messageHistories.value[index + 1].phoneNum);
        }
      })
      .catch(error => {
        console.error('Error deleting phone number:', error);
      });
  }
}

const showIcons = ref(null);
function deleteMessage(messageid) {
  const SelectedPhoneNum = messageHistories.value[IsSelectedItemID.value].phoneNum;
  console.log("SelectedPhoneNum", SelectedPhoneNum);

  axios.post('/delete-message', { id: messageid })
    .then(response => {
      console.log("Message deleted successfully");
      reloadingData(SelectedPhoneNum);
    })
}

function downloadMedia(itemValue) {
  // Extract filename from URL
  const item = "https://images.unsplash.com/photo-1545093149-618ce3bcf49d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=668&q=80";
  const filename = item.split('/').pop();
  console.log("filename", filename, "urlLink", item);
  // Fetch the file from the URL
  fetch(itemValue)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.blob();  // Convert the response to a Blob (file-like object of immutable, raw data)
    })
    .then(blob => {
      // Create a URL for the blob object
      const url = URL.createObjectURL(blob);
      console.log("urlLink", url);
      const a = document.createElement('a');
      document.body.appendChild(a);
      a.style.display = 'none';  // Hide the anchor element
      a.href = url;
      a.download = filename;  // Set the download attribute to the extracted filename
      a.click();  // Programmatically trigger a click on the anchor element

      // Clean up by revoking the created URL and removing the anchor tag
      URL.revokeObjectURL(url);
      document.body.removeChild(a);
    })
    .catch(error => {
      console.error('Error downloading the file:', error);
    });
}

function reloadingData(number) {

  axios.post('/get-message', { phonenumber: number })
    .then(response => {
      // Update the value of messageData with the response data
      messageData.value = response.data;
      // chatContainer.value = document.getElementById('chatContainer');
      // scrollToBottom();
    })

  phoneNumber.value = number;
}



function toggleMenu() {
  isVisibleMenu.value = !isVisibleMenu.value;
}

function onEnterPhone(event) {
  phoneNumber.value = event.target.value.trim();
  if (phoneNumber.value !== "") {
    const phoneNumberExists = messageHistories.value.find(history => history.phoneNum === phoneNumber.value);
    if (phoneNumberExists) {
      event.target.value = "";
      IsSelectedItemID.value = phoneNumberExists.id;
      showMessageHistory(phoneNumberExists.phoneNum, phoneNumberExists.time, phoneNumberExists.value);
      console.log("phonenumber is already existed!", phoneNumberExists);
    } else {
      messageHistories.value.push({
        id: id.value++,
        phoneNum: phoneNumber.value,
        lastword: "new user",
        history: [{
          message_id: message_id.value++,
          message_date: new Date().toISOString(),
          type: "1",
        },],
      });
      console.log("##$%$%", messageHistories.value);
      event.target.value = "";
    }
  }
}



function showMessageHistory(number, time, history) {

  showDetail.value = true;
  phoneNumber.value = number;
  histories.value = history;
  inputMessage.value = "";
  console.log("First");

  axios.post('/get-message', { phonenumber: number })
    .then(response => {
      // Update the value of messageData with the response data
      messageData.value = response.data;
      chatContainer.value = document.getElementById('chatContainer');
      scrollToBottom();
    })
}

const m = ref('');
function onEnter(event) {
  if (event.key === "Enter") {
    console.log("@@@&&&", phoneNumber.value);
    axios.post('/vue-message', { message: inputMessage.value, PhoneNumber: phoneNumber.value }).then(response => {
      m.value = response.data.message; // Assign the response data to the "m" variable
    }).then(() => {
      inputMessage.value = "";
      scrollToBottom();
    }).catch(error => {
      console.error('Error fetching message:', error);
    });
  }
}

function handleFileImport() {
  isSelecting.value = true;
  // Consider targeting a more specific file input if there are multiple. Example:
  // this.$refs.uploader.click(); // If using Vue component template refs
  document.querySelectorAll('input[type="file"]')[0].click(); // Adjust based on which input you want to trigger
  window.addEventListener("focus", () => {
    isSelecting.value = false;
  }, { once: true });
}


function onFileChanged(event) {
  const file = event.target.files[0]; // Directly access the file
  if (!file) {
    console.error('No file selected.');
    return;
  }

  let formData = new FormData();
  formData.append('file', file); // Append the selected file to form data
  formData.append('PhoneNumber', phoneNumber.value);

  axios.post('/media-message', formData) // Remove the Content-Type header
    .then(response => {
      scrollToBottom();
      console.log('Upload successful', response.data);
    }).catch(error => {
      console.error('Error uploading file:', error);
    });
}

// // Function to format the timestamp
// function formatTimestamp() {
//   const Timestamp = new Date();
//   // Extract hours and minutes
//   const hours = Timestamp.getHours();
//   const minutes = Timestamp.getMinutes();
  
//   // Determine AM/PM suffix based on hours
//   const ampm = hours >= 12 ? 'PM' : 'AM';
  
//   // Convert hours to 12-hour format
//   const formattedHours = hours % 12 || 12;
//   // Create the formatted time string
//   const formattedTime = `${formattedHours}:${minutes.toString().padStart(2, '0')} ${ampm}`;
//   console.log("formattedTime", formattedTime);
  
//   timeStamp.value = formattedTime;
// };

// // Call the updateClock function every second to update the time in real-time
// setInterval(formatTimestamp, 1000);

</script>

<style lang="scss">
@import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");

body {
  font-family: "Poppins", sans-serif;
}

input:focus {
  border: 1px solid;
  border-color: orange;
}

.input-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  max-width: 1100px;
  /* Set the maximum width as needed */
  margin: 0 auto;
  /* Center the input container */
}

.input-field {
  width: 95%;
  /* Reduce the width of the input field */
  padding: 10px;
  /* Add padding to the input field */
  box-sizing: border-box;
  /* Include padding in the width */
}

.icon-button {
  background: none;
  border: none;
  padding: 5px;
  cursor: pointer;
}

.rotate1 {
  transform: rotate(135deg);
  transition: transform 0.2s ease;
  /* Adjust the duration and timing function as needed */
}

.rotate2 {
  transform: rotate(-0deg);
  transition: transform 0.2s ease;
  /* Adjust the duration and timing function as needed */
}
</style>