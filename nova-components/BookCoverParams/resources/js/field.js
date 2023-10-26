import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-book-cover-params', IndexField)
  app.component('detail-book-cover-params', DetailField)
  app.component('form-book-cover-params', FormField)
})
