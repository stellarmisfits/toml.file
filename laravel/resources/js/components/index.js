import Vue from 'vue'
import Pill from './Pill'
import Card from './Card'
import Child from './Child'
import Modal from './Modal'
import Button from './Button'
import Dropdown from './Dropdown'
import EmptyList from './EmptyList'
import ListItem from './ListItem'
import Breadcrumbs from './Breadcrumbs'
import Checkbox from './Checkbox'
import { HasError, AlertError, AlertSuccess } from 'vform'
import TwCard from './TwCard'
import TwCheckbox from './TwCheckbox'
import Well from './Well'
import Logo from './Logo'

// Components that are registered globaly.
[
  TwCard,
  TwCheckbox,
  Pill,
  Card,
  Child,
  Modal,
  Button,
  Logo,
  Dropdown,
  EmptyList,
  ListItem,
  Breadcrumbs,
  Checkbox,
  HasError,
  AlertError,
  AlertSuccess,
  Well
].forEach(Component => {
  Vue.component(Component.name, Component)
})
