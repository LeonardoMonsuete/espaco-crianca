const cam = document.getElementById('cam')
const startVideo = () => {
    navigator.mediaDevices.enumerateDevices()
        .then(devices => {
            if (Array.isArray(devices)) {
                devices.forEach(device => {
                    if (device.kind === 'videoinput') {

                        if (device.label.includes(device.label)) {
                            navigator.getUserMedia(
                                {
                                    video: {
                                        deviceId: device.deviceId
                                    }
                                },
                                stream => cam.srcObject = stream,
                                error => console.error(error)
                            )
                        }
                    }
                })
            }
        })
}

const loadLabels = async () => {
    const url = './src/controllers/ajax/getPersonData.ajax.php'
    const responseLabels = await getPersonRegistrations("POST", url);
    const labels = JSON.parse("[" + responseLabels + "]")[0];

    return Promise.all(labels.map(async label => {
        const descriptions = []
        for (let i = 1; i <= 2; i++) {
            const img = await faceapi.fetchImage(`./media/pictures/person/${label}/img_0${i}.jpg`)
            const detections = await faceapi
                .detectSingleFace(img)
                .withFaceLandmarks()
                .withFaceDescriptor()
            descriptions.push(detections.descriptor)
        }
        return new faceapi.LabeledFaceDescriptors(label, descriptions)
    }))
}

Promise.all([
    faceapi.nets.tinyFaceDetector.loadFromUri('./src/assets/lib/face-api/models'),
    faceapi.nets.faceLandmark68Net.loadFromUri('./src/assets/lib/face-api/models'),
    faceapi.nets.faceRecognitionNet.loadFromUri('./src/assets/lib/face-api/models'),
    faceapi.nets.faceExpressionNet.loadFromUri('./src/assets/lib/face-api/models'),
    faceapi.nets.ageGenderNet.loadFromUri('./src/assets/lib/face-api/models'),
    faceapi.nets.ssdMobilenetv1.loadFromUri('./src/assets/lib/face-api/models'),
]).then(startVideo)

var count = 0;
var times = 0
cam.addEventListener('play', async () => {
    var MediaStream;
    const canvas = faceapi.createCanvasFromMedia(cam)
    const canvasSize = {
        width: cam.width,
        height: cam.height
    }
    const labels = await loadLabels()
    faceapi.matchDimensions(canvas, canvasSize)
    document.body.appendChild(canvas)

    var intervalId = setInterval(async () => {
        times++
        console.log(times)
        const detections = await faceapi
            .detectAllFaces(
                cam,
                new faceapi.TinyFaceDetectorOptions()
            )
            .withFaceLandmarks()
            .withFaceExpressions()
            .withAgeAndGender()
            .withFaceDescriptors()
        const resizedDetections = faceapi.resizeResults(detections, canvasSize)
        const faceMatcher = new faceapi.FaceMatcher(labels, 0.6)
        const results = resizedDetections.map(d =>
            faceMatcher.findBestMatch(d.descriptor)
        )

        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)
        faceapi.draw.drawDetections(canvas, resizedDetections)
        $(".loading-spinner-div")[0].classList.add('fade-out');
        $(".loading-spinner-div").hide("slow");
        $("#loading-camera").hide();
        let pessoa;
        results.forEach((result, index) => {
            const box = resizedDetections[index].detection.box
            if (!result.toString().includes('unknown')) {
                pessoa = result.toString().split("(")[0] 
                let porcentChance = parseInt(result.toString().split("(")[1].replace(/\D/g, ""));
                console.log(pessoa, porcentChance, count)
                if (porcentChance < 50) {
                    count++
                    clearInterval(intervalId); 
                    MediaStream = cam.srcObject.getTracks()[0];
                    MediaStream.stop()
                    const options = { boxColor: "#00FA9A" }
                    const drawBox = new faceapi.draw.DrawBox(box, options)
                    drawBox.draw(canvas)
                    $('#modal-processing-face-capture').modal('show');
                }
            }
        })
        
        if(times > 99){
            clearInterval(intervalId); 
        }

        if(count == 1){
            if(pessoa){
                window.location.href = './src/controllers/general/presenceController.php?pessoa=' + pessoa
            }
        }

        var urlToRedirect = "registra-presenca.php";
        if(window.confirm("Pessoa não foi reconhecida dentro do tempo estipulado, deseja tentar marcação pela matrícula ?")){
            urlToRedirect = "/espaco-crianca/";
        }
        window.location.href = urlToRedirect

    }, 100)


})


function getPersonRegistrations(method, url) {
    return new Promise(function (resolve, reject) {
        var formData = new FormData();
        formData.append('action', 'getAllRegistration')
        let xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(xhr.response);
            } else {
                reject({
                    status: this.status,
                    statusText: xhr.statusText
                });
            }
        };
        xhr.onerror = function () {
            reject({
                status: this.status,
                statusText: xhr.statusText
            });
        };
        xhr.send(formData);
    });
}


function delay(time) {
    return new Promise(resolve => setTimeout(resolve, time));
}





