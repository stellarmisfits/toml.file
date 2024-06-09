import axios from 'axios'

export const state = {
  orgs: [],
  linkedAccounts: [],
  unlinkedAccounts: [],
  linkedAssets: [],
  unlinkedAssets: [],
  linkedPrincipals: [],
  unlinkedPrincipals: [],
  linkedValidators: [],
  unlinkedValidators: [],
  toml: null
}

// getters
export const getters = {
  getOrgByUuid: (state) => (uuid) => {
    return state.orgs.find(org => org.uuid === uuid)
  },
  getOrgBySlug: (state) => (slug) => {
    return state.orgs.find(org => org.slug === slug)
  },
  orgs: state => (state.orgs.length) ? state.orgs : null,
  linkedAccounts: state => (state.linkedAccounts.length) ? state.linkedAccounts : null,
  unlinkedAccounts: state => state.unlinkedAccounts,

  linkedAssets: state => (state.linkedAssets.length) ? state.linkedAssets : null,
  unlinkedAssets: state => state.unlinkedAssets,

  linkedPrincipals: state => (state.linkedPrincipals.length) ? state.linkedPrincipals : null,
  unlinkedPrincipals: state => state.unlinkedPrincipals,

  linkedValidators: state => (state.linkedValidators.length) ? state.linkedValidators : null,
  unlinkedValidators: state => state.unlinkedValidators
}

// actions
export const actions = {
  async fetchOrgs ({ commit }) {
    const { data } = await axios.get('/api/organizations')
    commit('SET_ORGS', { orgs: data.data })
  },

  async fetchOrg ({ commit, getters }, uuid) {
    let org = getters.getOrgByUuid(uuid)

    if (!org) {
      const { data } = await axios.get('/api/organizations/' + uuid)
      commit('SET_ORG', { org: data.data })
    }
  },

  async fetchToml ({ commit }, org) {
    const { data } = await axios.get('/api/organizations/' + org.uuid + '/toml')
    commit('SET_TOML', { toml: data.toml })
  },

  async fetchLinkedAccounts ({ commit }, uuid) {
    const { data } = await axios.get('/api/accounts', { params: { 'linked_organization_uuid': uuid } })
    commit('SET_LINKED_ACCOUNTS', { accounts: data.data })
  },

  async fetchUnlinkedAccounts ({ commit }, uuid) {
    const { data } = await axios.get('/api/accounts', { params: { 'unlinked_organization_uuid': uuid } })
    commit('SET_UNLINKED_ACCOUNTS', { accounts: data.data })
  },

  async fetchLinkedAssets ({ commit }, uuid) {
    const { data } = await axios.get('/api/assets', { params: { 'linked_organization_uuid': uuid } })
    commit('SET_LINKED_ASSETS', { assets: data.data })
  },

  async fetchUnlinkedAssets ({ commit }, uuid) {
    const { data } = await axios.get('/api/assets', { params: { 'unlinked_organization_uuid': uuid } })
    commit('SET_UNLINKED_ASSETS', { assets: data.data })
  },

  async fetchLinkedPrincipals ({ commit }, uuid) {
    const { data } = await axios.get('/api/principals', { params: { 'linked_organization_uuid': uuid } })
    commit('SET_LINKED_PRINCIPALS', { principals: data.data })
  },

  async fetchUnlinkedPrincipals ({ commit }, uuid) {
    const { data } = await axios.get('/api/principals', { params: { 'unlinked_organization_uuid': uuid } })
    commit('SET_UNLINKED_PRINCIPALS', { principals: data.data })
  },

  async fetchLinkedValidators ({ commit }, uuid) {
    const { data } = await axios.get('/api/validators', { params: { 'linked_organization_uuid': uuid } })
    commit('SET_LINKED_VALIDATORS', { validators: data.data })
  },

  async fetchUnlinkedValidators ({ commit }, uuid) {
    const { data } = await axios.get('/api/validators', { params: { 'unlinked_organization_uuid': uuid } })
    commit('SET_UNLINKED_VALIDATORS', { validators: data.data })
  }
}

// mutations
export const mutations = {
  SET_ORGS (state, { orgs }) {
    state.orgs = orgs
  },
  SET_ORG (state, { org }) {
    state.orgs = [
      ...state.orgs.filter(element => element.uuid !== org.uuid),
      org
    ]
  },
  SET_TOML (state, { toml }) {
    state.toml = toml
  },
  SET_LINKED_ACCOUNTS (state, { accounts }) {
    state.linkedAccounts = accounts
  },
  SET_UNLINKED_ACCOUNTS (state, { accounts }) {
    state.unlinkedAccounts = accounts
  },
  SET_LINKED_ASSETS (state, { assets }) {
    state.linkedAssets = assets
  },
  SET_UNLINKED_ASSETS (state, { assets }) {
    state.unlinkedAssets = assets
  },
  SET_LINKED_PRINCIPALS (state, { principals }) {
    state.linkedPrincipals = principals
  },
  SET_UNLINKED_PRINCIPALS (state, { principals }) {
    state.unlinkedPrincipals = principals
  },
  SET_LINKED_VALIDATORS (state, { validators }) {
    state.linkedValidators = validators
  },
  SET_UNLINKED_VALIDATORS (state, { validators }) {
    state.unlinkedValidators = validators
  }
}
