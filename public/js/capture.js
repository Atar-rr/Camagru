var createphoto = document.getElementById('createphoto');

createphoto.addEventListener('click', function () {

    var video = document.getElementById('video');
    var makebutton = document.getElementById('makebutton');
    var canvas = document.getElementById('canvas');
    var photo = document.getElementById('photo');

    navigator.mediaDevices.getUserMedia({ video: { width: 900, height: 600 }, audio: false})
        .then(function (stream ) {
            video.srcObject = stream;
            video.play();
        }).catch(function (err) {
            console.log("An error occurred: " + err);
        });

    makebutton.addEventListener('click', function (ev) {
        var width = video.videoWidth;
        var height = video.videoHeight;
        var context = canvas.getContext('2d');
        var data = canvas.toDataURL('image/png');

        console.log(data);
        canvas.width = width;
        canvas.height = height;
        context.drawImage(video, 0, 0);

        //ajax
        const request = new XMLHttpRequest();
        const url = "/photo/save";
        photo.setAttribute('src', data);
        const params = 'photo=' + data;
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.addEventListener("readystatechange", () => {

            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
            }
        });
        request.send(params);
        //video.pause();
    });

}, false);
