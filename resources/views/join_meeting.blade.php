@extends('layouts.app')

@section('content')




<style>
  .player-name {
      font-size: 1.25rem; /* Slightly larger text for better visibility */
      font-weight: 500; /* Medium font weight */
      color: #333; /* Dark gray text for good contrast */
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); /* Subtle text shadow for better readability */
      margin-right: 1rem; /* Space to the right of the text */
      padding: 0.5rem; /* Padding around the text */
      border-radius: 0.25rem; /* Slightly rounded corners */
      background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background for contrast */
      display: inline-block; /* Ensure the background and padding wrap around the text */
      box-shadow: 0 0 3px rgba(0, 0, 0, 0.1); /* Subtle shadow for a raised effect */
  }
  
  .player {
      width: 150px; /* Fixed width for the video player */
      height: 100px; /* Fixed height for the video player */
      background-color: #000; /* Black background for the video player */
      border-radius: 0.25rem; /* Rounded corners for the video player */
  }



  .video-container {
      position: relative;
      width: 100%;
      height: 100%;
      background-color: #000;
      border-radius: 8px; /* Rounded corners for a modern look */
      overflow: hidden;   /* Hide any overflowing content */
  }
  video {
      width: 100%;
      height: 100%;
      object-fit: cover; /* Ensures video covers the container */
  }
  .controls-bar {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      z-index: 1000; /* Ensures controls stay above other content */
  }
  .btn-outline-info, .btn-outline-warning, .btn-outline-primary, .btn-outline-success, .btn-outline-danger {
      border-width: 2px; /* Thicker border for better visibility */
  }
  .btn-outline-info {
      color: #0dcaf0;
      border-color: #0dcaf0;
  }
  .btn-outline-warning {
      color: #ffc107;
      border-color: #ffc107;
  }
  .btn-outline-primary {
      color: #0d6efd;
      border-color: #0d6efd;
  }
  .btn-outline-success {
      color: #198754;
      border-color: #198754;
  }
  .btn-outline-danger {
      color: #dc3545;
      border-color: #dc3545;
  }
  .card-body {
      padding: 0;
  }

  .form-section {
            margin-bottom: 2rem;
        }
        .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
</style>


@if (session('owner_id'))
 
@endif




<div class="container mt-5  @if (session('owner_id')) d-none @else   @endif"" id="join_now_box">
  <!-- Header -->
  <header class="mb-4">
      <div class="d-flex justify-content-between align-items-center">
          <div class="logo">
              <h1> Meeting :- {{ @$meeting->title }}</h1>
          </div>
      </div>
  </header>

  <!-- Main Content -->
  <main>
      <div class="row">
          <!-- Create Meeting Form -->
          <div class="col-md-3 form-section"></div>
          <div class="col-md-6 form-section">
              <div class="card">
                  <div class="card-header">
                      <h2>What's Your Name</h2>
                  </div>
                  <div class="card-body p-4">
                      <form method="POST" action="{{ route('create.meeting') }}">
                          @csrf
                          <!-- Meeting Title -->
                          <div class="mb-3">
                              <label for="meetingTitle" class="form-label">Fullname</label>
                              <input type="text" class="form-control" id="meetingTitle" name="title" required placeholder="Enter fullname">
                          </div>

                                                         <!-- Save Button -->
                          <div class="d-flex justify-content-end">
                              <button id="join_now_btn" type="button" class="btn btn-primary">Join Now</button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
 
      </div>
  </main>

 
</div>


<div class="container mt-5  @if (session('owner_id')) @else d-none @endif"  id="main_meeting_box">
  <!-- Main Content -->
  <main>
      <div class="row">
          <!-- Host Screen -->
          <div class="col-md-8 d-flex flex-column">
              <!-- Video Container -->
              <div class="flex-grow-1 mb-2">
                  {{-- <div class="video-container">
                      <video id="hostVideo" autoplay muted></video>
                  </div> --}}
                  <div id="local-player" class="video-container" style="width:650px;height:500px;"></div>
                  
              </div>
              <!-- Controls at the Bottom -->
              <div class="controls-bar d-flex justify-content-between align-items-center px-3 py-2 bg-light border-top">
                <form id="join-form">
                  <button id="micToggle" class="btn btn-outline-info me-2">Mic: <span id="micStatus">Off</span></button>
                  <button id="cameraToggle" class="btn btn-outline-warning me-2">Camera: <span id="cameraStatus">Off</span></button>
                  <button id="shareScreen" class="btn btn-outline-primary me-2">Share Screen</button>
                  <button id="linkShare" class="btn btn-outline-success me-2">Share Link</button>
                  
                  <button id="join" type="submit" class="btn btn-primary btn-sm d-none ">Join</button>

                  <button id="leave" type="button" class="btn btn-outline-danger" disabled>End Meeting</button>

                  <button class="start-screen-share btn btn-info">Start Screen Share</button>
                  <button class="stop-screen-share btn btn-secondary" style="display: none;">Stop Screen Share</button>
                  

                 
                </form>
              </div>
          </div>


          

          <!-- Joinee Screen -->
          <div class="col-md-4 d-flex flex-column">
              <!-- Video Container -->
              <div class="flex-grow-1 mb-2">
                
                  <div id="remote-playerlist"></div>
                
              </div>
              <!-- Join Button at the Bottom -->
             
          </div>

  
      </div>
  </main>
</div>


{{--  mk --}}

<div class="container">
  <form id="join-form">
    <div class="row join-info-group d-none">
        <div class="col-sm">
          <p class="join-info-text">AppID</p>
          <input id="appid" type="text" placeholder="enter appid" required value="{{ env('AGORA_APP_ID') }}">
          <p class="tips">If you don`t know what is your appid, checkout <a href="https://docs.agora.io/en/Agora%20Platform/terms?platform=All%20Platforms#a-nameappidaapp-id">this</a></p>
        </div>
        <div class="col-sm">
          <p class="join-info-text">Token(optional)</p>
          <input id="token" type="text" placeholder="enter token" value="{{ $meeting->token }}">
          <p class="tips">If you don`t know what is your token, checkout <a href="https://docs.agora.io/en/Agora%20Platform/terms?platform=All%20Platforms#a-namekeyadynamic-key">this</a></p>
        </div>
        <div class="col-sm">
          <p class="join-info-text">Channel</p>
          <input id="channel" type="text" placeholder="enter channel name" required value="{{ $meeting->channel }}">
          <p class="tips">If you don`t know what is your channel, checkout <a href="https://docs.agora.io/en/Agora%20Platform/terms?platform=All%20Platforms#channel">this</a></p>
        </div>
    </div>

    
  </form>
  
</div>




<script src="{{ asset('agora/vendor/jquery-3.4.1.min.js')}}"></script>
<script src="{{ asset('agora/vendor/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('agora/AgoraRTC_N-4.21.0.js')}}"></script>
{{-- <script src="{{ asset('agora/index.js')}}"></script> --}}


<script>
  $(document).ready(function(){ 
   
    @auth 
       
    var session_owner_id = "{{ session('owner_id') }}".trim();;
    var login_user_id = " {{ auth()->user()->id }}".trim();;
    if(session_owner_id === login_user_id){
      $('#join').trigger('click'); // Trigger the custom event
    }
    @endauth


    $('#join_now_btn').click(function(){
      $('#join_now_box').addClass('d-none');
      $('#main_meeting_box').removeClass('d-none');
      $('#join').prop('disabled', false);
      $('#join').trigger('click');
    });
   
  });



  
</script>


<script>
  // create Agora client
var client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });

var localTracks = {
//screenTrack: null,
videoTrack: null,
audioTrack: null
};
var remoteUsers = {};
// Agora client options
var options = {
appid: null,
channel: null,
uid: null,
token: null
};

// the demo can auto join channel with params in url
$(() => {
var urlParams = new URL(location.href).searchParams;
options.appid = urlParams.get("appid");
options.channel = urlParams.get("channel");
options.token = urlParams.get("token");
if (options.appid && options.channel) {
  $("#appid").val(options.appid);
  $("#token").val(options.token);
  $("#channel").val(options.channel);
  $("#join-form").submit();
}
})

$("#join-form").submit(async function (e) {
e.preventDefault();
$("#join").attr("disabled", true);
try {
  options.appid = $("#appid").val();
  options.token = $("#token").val();
  options.channel = $("#channel").val();
  await join();
  if(options.token) {
    $("#success-alert-with-token").css("display", "block");
  } else {
    $("#success-alert a").attr("href", `index.html?appid=${options.appid}&channel=${options.channel}&token=${options.token}`);
    $("#success-alert").css("display", "block");
  }
} catch (error) {
  console.error(error);
} finally {
  $("#leave").attr("disabled", false);
}
})

$("#leave").click(function (e) {
leave();
})


// Function to toggle video on and off



// Function to start screen sharing
  async function startScreenShare() {
    try {
      const screenTrack = await AgoraRTC.createScreenVideoTrack();
      localTracks.screenTrack = screenTrack;
      await client.publish(screenTrack);
      document.querySelector('.start-screen-share').style.display = 'none';
      document.querySelector('.stop-screen-share').style.display = 'block';
      console.log('Screen sharing started');
    } catch (error) {
      console.error('Error starting screen share', error);
    }
  }

  // Function to stop screen sharing
  async function stopScreenShare() {
    if (localTracks.screenTrack) {
      await client.unpublish(localTracks.screenTrack);
      localTracks.screenTrack.close();
      localTracks.screenTrack = null;
      document.querySelector('.start-screen-share').style.display = 'block';
      document.querySelector('.stop-screen-share').style.display = 'none';
      console.log('Screen sharing stopped');
    }
  }

   // Add event listeners for screen sharing buttons
   document.querySelector(".start-screen-share").addEventListener("click", startScreenShare);
  document.querySelector(".stop-screen-share").addEventListener("click", stopScreenShare);

  

// Function to toggle video on and off
const cameraToggle = document.getElementById('cameraToggle');
 let cameraOn = false;
cameraToggle.addEventListener('click', () => {
    cameraOn = !cameraOn;
    cameraStatus.textContent = cameraOn ? 'On' : 'Off';
    // Implement camera on/off functionality here

     // For demo, toggling audio with camera
     if (cameraOn) {
      localTracks.videoTrack._mediaStreamTrack.muted=true;
      localTracks.videoTrack._mediaStreamTrack.enabled=false;
                } else {
                    localTracks.videoTrack._mediaStreamTrack.muted = false; // Unmute audio when camera is off
                    localTracks.videoTrack._mediaStreamTrack.enabled = true; // Enable audio track
                }
});


 
// Function to toggle video on and off
const micToggle = document.getElementById('micToggle');
let micOn = false;
micToggle.addEventListener('click', () => {
  micOn = !micOn;
  micStatus.textContent = micOn ? 'On' : 'Off';
    // Implement camera on/off functionality here

     // For demo, toggling audio with camera
     if (micOn) {
      localTracks.audioTrack._mediaStreamTrack.muted=true;
      localTracks.audioTrack._mediaStreamTrack.enabled=false;
                } else {
                    localTracks.audioTrack._mediaStreamTrack.muted = false; // Unmute audio when camera is off
                    localTracks.audioTrack._mediaStreamTrack.enabled = true; // Enable audio track
                }
});
 


async function join() {

// add event listener to play remote tracks when remote user publishs.
client.on("user-published", handleUserPublished);
client.on("user-unpublished", handleUserUnpublished);

// join a channel and create local tracks, we can use Promise.all to run them concurrently
[ options.uid, localTracks.audioTrack, localTracks.videoTrack ] = await Promise.all([
  // join the channel
  client.join(options.appid, options.channel, options.token || null),
  // create local tracks, using microphone and camera
  AgoraRTC.createMicrophoneAudioTrack(),
  AgoraRTC.createCameraVideoTrack()
]);

 console.log(options.uid);

// play local video track
localTracks.videoTrack.play("local-player");
$("#local-player-name").text(`localVideo(${options.uid})`);

// publish local tracks to channel
await client.publish(Object.values(localTracks));
console.log("publish success");

 

}

async function leave() {
for (trackName in localTracks) {
  var track = localTracks[trackName];
  if(track) {
    track.stop();
    track.close();
    localTracks[trackName] = undefined;
  }
}

// remove remote users and player views
remoteUsers = {};
$("#remote-playerlist").html("");

// leave the channel
await client.leave();

$("#local-player-name").text("");
$("#join").attr("disabled", false);
$("#leave").attr("disabled", true);
console.log("client leaves channel success");
}

async function subscribe(user, mediaType) {
const uid = user.uid;
// subscribe to a remote user
await client.subscribe(user, mediaType);
console.log("subscribe success");
if (mediaType === 'video') {
  const player = $(`
    <div id="player-wrapper-${uid}">
      <p class="player-name">remoteUser(${uid})</p>
      <div id="player-${uid}" class="player"></div>
    </div>
  `);
  $("#remote-playerlist").append(player);
  user.videoTrack.play(`player-${uid}`);
}
if (mediaType === 'audio') {
  user.audioTrack.play();
}
}

function handleUserPublished(user, mediaType) {
const id = user.uid;
remoteUsers[id] = user;
subscribe(user, mediaType);
}

function handleUserUnpublished(user) {
const id = user.uid;
delete remoteUsers[id];
$(`#player-wrapper-${id}`).remove();
}
</script>

{{-- mk --}}

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
      const micToggle = document.getElementById('micToggle');
      const cameraToggle = document.getElementById('cameraToggle');
      const micStatus = document.getElementById('micStatus');
      const cameraStatus = document.getElementById('cameraStatus');
      const hostVideo = document.getElementById('hostVideo');
      const joineeVideo = document.getElementById('joineeVideo');
      const endMeeting = document.getElementById('endMeeting');
      const joinMeeting = document.getElementById('joinMeeting');

      let micOn = false;
      let cameraOn = false;

     
      // });

      document.getElementById('shareScreen').addEventListener('click', async () => {
          try {
              const stream = await navigator.mediaDevices.getDisplayMedia({ video: true });
              hostVideo.srcObject = stream;
              // Implement screen sharing functionality here
          } catch (err) {
              console.error('Error sharing screen:', err);
          }
      });

      document.getElementById('linkShare').addEventListener('click', () => {
          // Implement link sharing functionality here
          prompt('Share this link:', window.location.href);
      });

      endMeeting.addEventListener('click', () => {
          // Implement end meeting functionality here
          alert('Meeting has been ended.');
          // Stop all streams and cleanup
          const streams = [hostVideo.srcObject, joineeVideo.srcObject];
          streams.forEach(stream => {
              if (stream) {
                  stream.getTracks().forEach(track => track.stop());
              }
          });
          hostVideo.srcObject = null;
          joineeVideo.srcObject = null;
      });

      joinMeeting.addEventListener('click', () => {
          // Implement join meeting functionality here
          alert('Joining meeting...');
          // Start receiving video stream from the meeting
      });
  });
</script>


 
 
 
@endsection


