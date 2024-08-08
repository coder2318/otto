<script lang="ts">
  import { confirm, prompt } from '@nativescript/core/ui/dialogs'
  import { isAndroid, isIOS } from '@nativescript/core/platform'
  import { knownFolders } from '@nativescript/core/file-system'
  import { AudioPlayerOptions, AudioRecorderOptions, TNSPlayer, TNSRecorder } from 'nativescript-audio'
  import { getBase64String, requestErrorCallback, secondsToTime } from '@/utils/app'
  import { uploadAudioAndCreateChapterDraft } from '~/shared/recordService'
  import { BACKEND_URL } from '~/constants'

  const audioFolder = knownFolders.currentApp().getFolder('recordings')

  let recorder: TNSRecorder

  let recording: boolean
  let fileExists: boolean
  let uploading: boolean
  let uploaded: boolean

  let audioFileName = isIOS ? 'audio.caf' : 'audio.mp4'
  let errors: any

  let recordingPause: boolean = false
  let recordingMaxTime: number = 1000 * 60 * 10
  let recordingTimer: number = 0
  let recordingTimerInterval: any = null

  const startRecord = async () => {
    recorder = new TNSRecorder()

    let androidFormat
    let androidEncoder
    if (isAndroid) {
      androidFormat = 2
      androidEncoder = 3
    }

    let options: AudioRecorderOptions = {
      filename: `${audioFolder.path}/${audioFileName}`,
      format: androidFormat,
      encoder: androidEncoder,
      errorCallback: (error: any) => {
        console.error('Error while recording:', error)
      },
    }

    recordingTimer = 0
    recordingTimerInterval = setInterval(() => {
      recordingTimer += 100
    }, 100)

    await recorder.start(options)
    recording = true
  }

  const stopRecord = async () => {
    confirm({
      title: 'Are you sure you want to stop this recording?',
      okButtonText: 'Stop',
      cancelButtonText: 'Cancel',
    }).then((res) => {
      if (res) {
        recorder.stop()

        clearInterval(recordingTimerInterval)

        recording = false
        recordingPause = false
        fileExists = true
      }
    })
  }

  const pauseRecord = async () => {
    await recorder.pause()

    clearInterval(recordingTimerInterval)

    recordingPause = true
  }

  const continueRecord = async () => {
    await recorder.resume()

    recordingTimerInterval = setInterval(() => {
      recordingTimer += 100
    }, 100)

    recording = true
    recordingPause = false
  }

  const play = async () => {
    const player = new TNSPlayer()

    await player.playFromFile(<AudioPlayerOptions>{
      audioFile: `${audioFolder.path}/${audioFileName}`,
    })
  }

  const upload = async () => {
    prompt({
      title: 'Enter chapter title',
      okButtonText: 'Upload',
      cancelButtonText: 'Cancel',
      defaultText: '',
    }).then((response) => {
      if (response.result) {
        const { text } = response
        const file = audioFolder.getFile(`${audioFileName}`)

        const payload = {
          title: text,
          record: getBase64String(file)
        }

        uploadAudioAndCreateChapterDraft(
          payload,
          (response: any) => {
            uploading = false
            uploaded = true
            const newChapterUrl = `${BACKEND_URL}/chapters/${response?.chapter?.id}/write`

            alert({
              title: 'Success!',
              message: `Uploaded to server. Chapter link: ${newChapterUrl}`,
              okButtonText: 'OK',
              cancelable: true,
            })
          },
          (error: any) => {
            uploading = false
            uploaded = true

            requestErrorCallback(error, {
              message: 'Error while uploading recording: ',
            })
          }
        )
      }
    })
  }
</script>

<page>
  <actionBar title="Otto Story" />

  <stackLayout class="form">
    <flexboxLayout>
      <label class="text-info" text={secondsToTime(recordingTimer)} />
      <label class="text-info" text="/ {secondsToTime(recordingMaxTime)}" />
    </flexboxLayout>

    {#if !recording}
      <button text="Record" on:tap={startRecord} />
      <label class="text-info" fontWeight="bold" text="Press to Start Recording" />
    {/if}

    {#if recording}
      <gridLayout columns="*,*" height="70">
        {#if recordingPause}
          <button text="Сontinue 🔴" col={0} on:tap={continueRecord} />
        {:else}
          <button text="Pause 🔴" col={0} on:tap={pauseRecord} />
        {/if}

        {#if recording}
          <button text="Stop" col={1} on:tap={stopRecord} />
        {/if}
      </gridLayout>
    {/if}

    {#if fileExists && !recording}
      <button text="Play" col={2} on:tap={play} />
      <button text="Upload" on:tap={upload} hidden={uploading} />
    {/if}

    {#if uploading}
      <activityIndicator busy={true} />
      <label class="text-info" text="Uploading..." />
    {/if}

    {#if errors?.title}
      <label class="text-error" text={errors.title} />
    {/if}
    {#if errors?.timeline_id}
      <label class="text-error" text={errors.timeline_id} />
    {/if}
  </stackLayout>
</page>

<style>
  FlexboxLayout {
    justify-content: center;
    align-items: center;
    background-size: cover;
  }

  Button {
    margin-bottom: 10;
  }

  .text-info {
    color: #000;
    text-align: center;
  }
  
  .text-error {
    color: #f87272;
  }

  actionBar {
    background-color: #0c335a;
  }

  page {
    background-color: #f2eee9;
  }
</style>
