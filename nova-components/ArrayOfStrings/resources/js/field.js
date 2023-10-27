import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-array-of-strings', IndexField)
  app.component('detail-array-of-strings', DetailField)
  app.component('form-array-of-strings', FormField)
})
