import axios from 'axios'
import { fetchLinkedOrgs, fetchUnlinkedOrgs } from '~/store/linked-orgs'

export const state = {
  accounts: [],
  challenge: {
    transaction: null,
    network_passphrase: null
  },
  linkedOrgs: [],
  unlinkedOrgs: []
}

// getters
export const getters = {
  getAccountByUuid: (state) => (uuid) => {
    return state.accounts.find(account => account.uuid === uuid)
  },
  getAccountBySlug: (state) => (slug) => {
    return state.accounts.find(account => account.slug === slug)
  },
  accounts: state => (state.accounts.length) ? state.accounts : null,
  challenge: state => state.challenge.transaction,
  linkedOrgs: state => state.linkedOrgs,
  unlinkedOrgs: state => state.unlinkedOrgs,
  organization: (state) => state.linkedOrgs[0]
}

// actions
export const actions = {
  async fetchAccounts ({ commit }) {
    const { data } = await axios.get('/api/accounts')
    commit('SET_ACCOUNTS', { accounts: data.data })
  },

  async fetchAccount ({ commit, getters }, uuid) {
    let account = getters.getAccountByUuid(uuid)

    if (!account) {
      const { data } = await axios.get('/api/accounts/' + uuid)
      commit('SET_ACCOUNT', { account: data.data })
    }
  },

  fetchLinkedOrgs,
  fetchUnlinkedOrgs
}

// mutations
export const mutations = {
  SET_ACCOUNTS (state, { accounts }) {
    state.accounts = accounts
  },
  SET_ACCOUNT (state, { account }) {
    state.accounts = [
      ...state.accounts.filter(element => element.uuid !== account.uuid),
      account
    ]
  },
  SET_LINKED_ORGS (state, { orgs }) {
    state.linkedOrgs = orgs
  },
  SET_UNLINKED_ORGS (state, { orgs }) {
    state.unlinkedOrgs = orgs
  },
  SET_CHALLENGE (state, { challenge }) {
    state.challenge = challenge
  }
}
