import dayjs from 'dayjs'
import 'dayjs/locale/en'
import LocalizedFormat from 'dayjs/plugin/localizedFormat'
import RelativeTime from 'dayjs/plugin/relativeTime'

dayjs.extend(LocalizedFormat)
dayjs.extend(RelativeTime)

export { dayjs }
