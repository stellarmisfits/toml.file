import store from '~/store'

export default async (to, from, next) => {
  // continue if not authenticated
  if (!store.getters['auth/check']) {
    next()
    return
  }

  // continue if there's no user
  const user = store.getters['auth/user']
  if (!user) {
    next()
    return
  }

  // continue if the user is accessing pages that don't require agreements
  const pages = ['welcome', 'terms', 'privacy', 'login']
  if (pages.includes(to.name)) {
    next()
    return
  }

  // redirect to agreement if the user hasn't agreed to the terms
  if (!user.agreed_to_terms && to.name !== 'agreement') {
    next({ name: 'agreement' })
    return
  }

  // redirect away from agreement if the user has agreed to the terms
  if (user.agreed_to_terms && to.name === 'agreement') {
    next({ name: 'dashboard' })
    return
  }

  next()
}
