<!-- <div class="row">
  <video id="player" controls autoplay></video>
  <button id="capture">Capture</button>
</div>
<canvas id="canvas" width=320 height=240></canvas>
<script>
  const player = document.getElementById('player');
  const canvas = document.getElementById('canvas');
  const context = canvas.getContext('2d');
  const captureButton = document.getElementById('capture');

  const constraints = {
    video: true,
  };

  captureButton.addEventListener('click', () => {
    context.drawImage(player, 0, 0, canvas.width, canvas.height);

    // Stop all video streams.
    player.srcObject.getVideoTracks().forEach(track => track.stop());
  });

  navigator.mediaDevices.getUserMedia(constraints)
    .then((stream) => {
      // Attach the video stream to the video element and autoplay.
      player.srcObject = stream;
    });
</script> -->

<div id="camera"></div>
   <!-- <script src="https://serratus.github.io/quaggaJS/examples/js/quagga.min.js" type="text/javascript"></script> -->
   <script src="/static/quagga.min.js" type="text/javascript"></script>
   <script>
       Quagga.init({
           numOfWorkers: 4,
           frequency: 10,
           locate: true,
           inputStream : {
               name : "Live",
               type : "LiveStream",
               target: document.querySelector('#camera'),
               constraints: {
                   width: { min: 640, ideal: 1280, max: 1920 },
                   height: { min: 480, ideal: 720, max: 1080 },
                   facingMode: "environment", // or user
                   frameRate: 10,
               }
           },
           decoder : {
               readers: [
                   'ean_reader'
               ]
           },
           locator: {
               patchSize: "medium", // x-small, small, medium, large, x-large
           }
       }, function(err) {
           if (err) {
               console.log(err);
               return
           }
           console.log("Initialization finished. Ready to start");
           Quagga.start();
       });

       Quagga.onProcessed(function(result) {
           var drawingCtx = Quagga.canvas.ctx.overlay,
               drawingCanvas = Quagga.canvas.dom.overlay;

           if (result) {
               if (result.boxes) {
                   drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                   result.boxes.filter(function (box) {
                       return box !== result.box;
                   }).forEach(function (box) {
                       Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
                   });
               }

               if (result.box) {
                   Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
               }

               if (result.codeResult && result.codeResult.code) {
                   Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
               }
           }
       });

       Quagga.onDetected(function(result) {
           var isbn = result.codeResult.code;
           if (isbn.match(/^97[8|9]/)) {
               var style = window.innerHeight > window.innerWidth ? 'width: ' + window.innerWidth + 'px;' : 'height: ' + window.innerHeight + 'px;';
               document.getElementById('camera').innerHTML = '<img src="https://cover.openbd.jp/' + isbn + '.jpg" alt="" style="' + style + '">';
               var params = getQueryString();
               if (params) {
                   var url = params.url + '?' + params.param + '=' + isbn;
                   setTimeout(function() {
                       location.href = url;
                   }, 300);
               } else {
                   alert(isbn);
               }
           }
       });

       function getQueryString() {
           if (location.search==='') return null;
           var params = {}
           location.search.substr(1).split('&').map(function(param) {
               var pairs = param.split('=');
               params[pairs[0]] = decodeURIComponent(pairs[1]);
           });
           return params;
       }
   </script>
