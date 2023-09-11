import dayjs from 'dayjs'
import 'dayjs/locale/en'
import LocalizedFormat from 'dayjs/plugin/localizedFormat'

dayjs.extend(LocalizedFormat)

export { dayjs }
