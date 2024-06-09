import store from '~/store'

export default async (to, from, next) => {
  if (store.getters['auth/check'] && !store.getters['org/orgs']) {
    try {
      await store.dispatch('org/fetchOrgs')
    } catch (e) { }
  }

  next()
}
