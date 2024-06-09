import Vue from 'vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// import { } from '@fortawesome/free-regular-svg-icons'

import {
  faBuilding,
  faChevronCircleDown,
  faChevronCircleRight,
  faCog,
  faEdit,
  faEllipsisH,
  faExternalLinkAlt,
  faFile,
  faImage,
  faKey,
  faLock,
  faMoneyBillAlt,
  faPlusCircle,
  faSignOutAlt,
  faTimesCircle,
  faUser
} from '@fortawesome/free-solid-svg-icons'

import {
  faGithub
} from '@fortawesome/free-brands-svg-icons'

library.add(
  faBuilding,
  faChevronCircleDown,
  faChevronCircleRight,
  faCog,
  faEdit,
  faEllipsisH,
  faExternalLinkAlt,
  faFile,
  faGithub,
  faImage,
  faKey,
  faLock,
  faMoneyBillAlt,
  faPlusCircle,
  faSignOutAlt,
  faTimesCircle,
  faUser
)

Vue.component('fa', FontAwesomeIcon)
