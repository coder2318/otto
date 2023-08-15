import Cropper from 'cropperjs'

export function createCropperForFilepond(
    element: HTMLElement = document.body,
    options: Cropper.Options<HTMLImageElement> = {}
) {
    return {
        file: null as Blob | null,
        image: null,
        cropper: null as Cropper | null,
        open(file: Blob) {
            this.file = file
            this.image = document.createElement('img')
            this.image.style.display = 'none'
            element.appendChild(this.image)
            this.image.src = URL.createObjectURL(this.file)
            this.cropper?.destroy()
            this.cropper = new Cropper(this.image, {
                ...options,
            })
        },

        getOptions() {
            return getEditedOptions(this.cropper)
        },
        clear() {
            this.cropper?.destroy()
            this.cropper = null
            URL.revokeObjectURL(this.file)
            this.image?.remove()
        },
    }
}

function getEditedOptions(cropper: Cropper) {
    const canvasData = cropper.getCanvasData()
    const cropData = cropper.getData()
    const imageData = cropper.getImageData()
    /* coordinates of each corner of the original image with the origin at the center of the canvas (rotation point) */
    const offsetTopLeftX = -imageData.naturalWidth / 2
    const offsetTopLeftY = -imageData.naturalHeight / 2
    const offsetTopRightX = imageData.naturalWidth / 2
    const offsetTopRightY = -imageData.naturalHeight / 2
    const offsetBottomLeftX = -imageData.naturalWidth / 2
    const offsetBottomLeftY = imageData.naturalHeight / 2
    const offsetBottomRightX = imageData.naturalWidth / 2
    const offsetBottomRightY = imageData.naturalHeight / 2

    /* apply rotation to each corner */
    const rotatedTopLeftX =
        offsetTopLeftX * Math.cos((cropData.rotate * Math.PI) / 180) -
        offsetTopLeftY * Math.sin((cropData.rotate * Math.PI) / 180)
    const rotatedTopLeftY =
        offsetTopLeftX * Math.sin((cropData.rotate * Math.PI) / 180) +
        offsetTopLeftY * Math.cos((cropData.rotate * Math.PI) / 180)
    const rotatedTopRightX =
        offsetTopRightX * Math.cos((cropData.rotate * Math.PI) / 180) -
        offsetTopRightY * Math.sin((cropData.rotate * Math.PI) / 180)
    const rotatedTopRightY =
        offsetTopRightX * Math.sin((cropData.rotate * Math.PI) / 180) +
        offsetTopRightY * Math.cos((cropData.rotate * Math.PI) / 180)
    const rotatedBottomLeftX =
        offsetBottomLeftX * Math.cos((cropData.rotate * Math.PI) / 180) -
        offsetBottomLeftY * Math.sin((cropData.rotate * Math.PI) / 180)
    const rotatedBottomLeftY =
        offsetBottomLeftX * Math.sin((cropData.rotate * Math.PI) / 180) +
        offsetBottomLeftY * Math.cos((cropData.rotate * Math.PI) / 180)
    const rotatedBottomRightX =
        offsetBottomRightX * Math.cos((cropData.rotate * Math.PI) / 180) -
        offsetBottomRightY * Math.sin((cropData.rotate * Math.PI) / 180)
    const rotatedBottomRightY =
        offsetBottomRightX * Math.sin((cropData.rotate * Math.PI) / 180) +
        offsetBottomRightY * Math.cos((cropData.rotate * Math.PI) / 180)

    /* offset coordinates so origin is the top left corner of the rotated canvas (ie use canvasData width and height) */
    const translatedTopLeftX = rotatedTopLeftX + canvasData.naturalWidth / 2
    const translatedTopLeftY = rotatedTopLeftY + canvasData.naturalHeight / 2
    const translatedTopRightX = rotatedTopRightX + canvasData.naturalWidth / 2
    const translatedTopRightY = rotatedTopRightY + canvasData.naturalHeight / 2
    const translatedBottomLeftX =
        rotatedBottomLeftX + canvasData.naturalWidth / 2
    const translatedBottomLeftY =
        rotatedBottomLeftY + canvasData.naturalHeight / 2
    const translatedBottomRightX =
        rotatedBottomRightX + canvasData.naturalWidth / 2
    const translatedBottomRightY =
        rotatedBottomRightY + canvasData.naturalHeight / 2

    /* Center point of crop area in rotated coordinates */
    let centerX = cropData.x + cropData.width / 2
    let centerY = cropData.y + cropData.height / 2

    let distances = []

    distances.push(
        getCenterPointToIntersectionDistance(
            centerX,
            centerY,
            centerX + cropData.width / 2,
            centerY + cropData.height / 2,
            translatedTopLeftX,
            translatedTopLeftY,
            translatedTopRightX,
            translatedTopRightY
        )
    )
    distances.push(
        getCenterPointToIntersectionDistance(
            centerX,
            centerY,
            centerX + cropData.width / 2,
            centerY + cropData.height / 2,
            translatedTopLeftX,
            translatedTopLeftY,
            translatedBottomLeftX,
            translatedBottomLeftY
        )
    )
    distances.push(
        getCenterPointToIntersectionDistance(
            centerX,
            centerY,
            centerX + cropData.width / 2,
            centerY + cropData.height / 2,
            translatedBottomLeftX,
            translatedBottomLeftY,
            translatedBottomRightX,
            translatedBottomRightY
        )
    )
    distances.push(
        getCenterPointToIntersectionDistance(
            centerX,
            centerY,
            centerX + cropData.width / 2,
            centerY + cropData.height / 2,
            translatedTopRightX,
            translatedTopRightY,
            translatedBottomRightX,
            translatedBottomRightY
        )
    )

    distances.push(
        getCenterPointToIntersectionDistance(
            centerX,
            centerY,
            centerX + cropData.width / 2,
            centerY - cropData.height / 2,
            translatedTopLeftX,
            translatedTopLeftY,
            translatedTopRightX,
            translatedTopRightY
        )
    )
    distances.push(
        getCenterPointToIntersectionDistance(
            centerX,
            centerY,
            centerX + cropData.width / 2,
            centerY - cropData.height / 2,
            translatedTopLeftX,
            translatedTopLeftY,
            translatedBottomLeftX,
            translatedBottomLeftY
        )
    )
    distances.push(
        getCenterPointToIntersectionDistance(
            centerX,
            centerY,
            centerX + cropData.width / 2,
            centerY - cropData.height / 2,
            translatedBottomLeftX,
            translatedBottomLeftY,
            translatedBottomRightX,
            translatedBottomRightY
        )
    )
    distances.push(
        getCenterPointToIntersectionDistance(
            centerX,
            centerY,
            centerX + cropData.width / 2,
            centerY - cropData.height / 2,
            translatedTopRightX,
            translatedTopRightY,
            translatedBottomRightX,
            translatedBottomRightY
        )
    )

    // remove false values from array
    distances = distances.filter(function (el) {
        return el !== false
    })

    const shortestDistance = Math.min(...distances)
    // gets the zoom from shortest distance and half diagonal of crop area
    const zoom =
        shortestDistance /
        Math.sqrt(
            Math.pow(cropData.width / 2, 2) + Math.pow(cropData.height / 2, 2)
        )

    /* Center point in the non-rotated image coordinates (for filepond) */

    /* offset coordinates so origin is the center of the canvas (rotation point) */
    const offsetX = centerX - canvasData.naturalWidth / 2
    const offsetY = centerY - canvasData.naturalHeight / 2

    /* apply reverse rotation to the point */
    const rotatedX =
        offsetX * Math.cos((-cropData.rotate * Math.PI) / 180) -
        offsetY * Math.sin((-cropData.rotate * Math.PI) / 180)
    const rotatedY =
        offsetX * Math.sin((-cropData.rotate * Math.PI) / 180) +
        offsetY * Math.cos((-cropData.rotate * Math.PI) / 180)

    /* offset coordinates so origin is the top left corner of the unrotated canvas (ie use imageData width and height) */
    const translatedX = rotatedX + imageData.naturalWidth / 2
    const translatedY = rotatedY + imageData.naturalHeight / 2

    centerX = translatedX
    centerY = translatedY

    /* Ratio of selected crop area. */
    const cropAreaRatio = cropData.height / cropData.width

    /* Center point mapped to a [0,1] range (that's what filepond waits for) */
    const mappedCenterX = centerX / imageData.naturalWidth
    const mappedCenterY = centerY / imageData.naturalHeight

    const filepondCropData = {
        data: {
            crop: {
                center: {
                    x: mappedCenterX,
                    y: mappedCenterY,
                },
                flip: {
                    horizontal: cropData.scaleX < 0,
                    vertical: cropData.scaleY < 0,
                },
                zoom: zoom,
                rotation: (Math.PI / 180) * cropData.rotate,
                aspectRatio: cropAreaRatio,
            },
        },
    }

    return filepondCropData
}

function getCenterPointToIntersectionDistance(
    cropCenterX,
    cropCenterY,
    cropDirectionPointX,
    cropDirectionPointY,
    linePointX1,
    linePointY1,
    linePointX2,
    linePointY2
) {
    const intersectionPoint = intersect(
        cropCenterX,
        cropCenterY,
        cropDirectionPointX,
        cropDirectionPointY,
        linePointX1,
        linePointY1,
        linePointX2,
        linePointY2
    )
    if (intersectionPoint) {
        const distance = Math.sqrt(
            Math.pow(intersectionPoint.x - cropCenterX, 2) +
                Math.pow(intersectionPoint.y - cropCenterY, 2)
        )
        return distance
    }
    return false
}

function intersect(x1, y1, x2, y2, x3, y3, x4, y4) {
    if ((x1 === x2 && y1 === y2) || (x3 === x4 && y3 === y4)) return false // Zero length line
    const denominator = (y4 - y3) * (x2 - x1) - (x4 - x3) * (y2 - y1)
    if (denominator === 0) return false // Lines are parallel
    const ua = ((x4 - x3) * (y1 - y3) - (y4 - y3) * (x1 - x3)) / denominator
    const x = x1 + ua * (x2 - x1)
    const y = y1 + ua * (y2 - y1)
    return { x, y }
}
