import { Http } from '@nativescript/core'
import { BACKEND_URL } from '~/constants'
import { requestErrorCallback } from '~/utils/app'

export async function uploadAudioAndCreateChapterDraft(
  content: any,
  callback?: (response: { chapter: { id: number } }) => void,
  errorCallback?: (error: { message?: string }) => void
) {
  const url = `${BACKEND_URL}/mobile/upload`

  Http.request({
    url,
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    content: JSON.stringify(content),
  })
    .then((response) => {
      const json = response.content?.toJSON()
      if (response.statusCode !== 200) {
        console.error('Upload error: ', json)

        if (errorCallback) {
          errorCallback(json)
        } else {
          requestErrorCallback(json)
        }

        return
      }

      if (callback) {
        callback(json)
      }
    })
    .catch((error) => {
      console.error('Upload error: ', error)

      if (errorCallback) {
        errorCallback(error)
      } else {
        requestErrorCallback(error)
      }
    })
}
