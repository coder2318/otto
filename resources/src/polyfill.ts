import MediaRecorder from 'audio-recorder-polyfill'

if (typeof MediaRecorder !== 'undefined') {
    window.MediaRecorder = MediaRecorder
}
