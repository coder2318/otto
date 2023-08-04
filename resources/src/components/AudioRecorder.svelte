<script lang="ts">
    let player: HTMLAudioElement
    let mediaRecorder: MediaRecorder

    function changer(event: Event) {
        // @ts-ignore
        const file = event.currentTarget.files[0]
        const url = URL.createObjectURL(file)
        // Do something with the audio file.
        player.src = url
    }

    function startRecording() {
        navigator.mediaDevices
            .getUserMedia({ audio: true, video: false })
            .then((stream) => {
                const recordedChunks = []
                mediaRecorder = new MediaRecorder(stream, {
                    mimeType: 'audio/webm',
                })

                mediaRecorder.addEventListener('dataavailable', function (e) {
                    if (e.data.size > 0) recordedChunks.push(e.data)
                })

                mediaRecorder.addEventListener('stop', function () {
                    player.src = URL.createObjectURL(new Blob(recordedChunks))
                })

                mediaRecorder.start()
            })
    }

    function stopRecording() {
        mediaRecorder.stop()
    }
</script>

<button on:click|preventDefault={startRecording}>Start Recording</button>
<button on:click|preventDefault={stopRecording}>Stop Recording</button>
<audio id="player" controls bind:this={player} />
