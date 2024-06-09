import axios from 'axios'
import { fetchLinkedOrgs, fetchUnlinkedOrgs } from '~/store/linked-orgs'

export const state = {
  principals: [],
  linkedOrgs: [],
  unlinkedOrgs: []
}

// getters
export const getters = {
  getPrincipalByUuid: (state) => (uuid) => {
    return state.principals.find(principal => principal.uuid === uuid)
  },
  principals: state => (state.principals.length) ? state.principals : null,
  linkedOrgs: state => state.linkedOrgs,
  unlinkedOrgs: state => state.unlinkedOrgs
}

// actions
export const actions = {
  async fetchPrincipals ({ commit }) {
    const { data } = await axios.get('/api/principals')
    commit('SET_PRINCIPALS', { principals: data.data })
  },

  async fetchPrincipal ({ commit, getters }, uuid) {
    let principal = getters.getPrincipalByUuid(uuid)

    if (!principal) {
      const { data } = await axios.get('/api/principals/' + uuid)
      commit('SET_PRINCIPAL', { principal: data.data })
    }
  },

  fetchLinkedOrgs,
  fetchUnlinkedOrgs
}

// mutations
export const mutations = {
  SET_PRINCIPALS (state, { principals }) {
    state.principals = principals
  },
  SET_PRINCIPAL (state, { principal }) {
    state.principals.push(principal)
  },
  SET_LINKED_ORGS (state, { orgs }) {
    state.linkedOrgs = orgs
  },
  SET_UNLINKED_ORGS (state, { orgs }) {
    state.unlinkedOrgs = orgs
  }
}
